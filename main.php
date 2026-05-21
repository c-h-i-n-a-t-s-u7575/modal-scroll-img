<?php
/*
Plugin Name: modal-scrill-img
Description: スクロールで広告がモーダルで出る
*/

/*↓ここからhttps://yosiakatsuki.net/blog/add-original-menu-page/をコピペ*/
add_action( 'admin_menu', 'my_add_admin_menu' );
/**
 * 「設定」にメニューを追加
 */
function my_add_admin_menu() {
  //add_options_page(　設定画面の中に表示
  add_menu_page(
    'モーダル画像', // 設定画面のページタイトル.
    'モーダル画像', // 管理画面メニューに表示される名前.
    'manage_options',
    'my-original-menu', // メニューのスラッグ.
    'my_original_menu_page' // メニューの中身を表示させる関数の名前.
  );
}

/**
 * メニューページの中身を作成
 */
function my_original_menu_page() {
  //ここがアウトプットPHP
  //ここで4つのPOST値が来ているかを判定
  if (!empty($_POST['image_path'])
    && !empty($_POST['top'])
    && !empty($_POST['cat_id'])
    && !empty($_POST['url'])
  ) {
    //サニタライズ(入力データなどに含まれる不正なコードや危険な文字を検出し、無害化（消毒）する処理)もする
    $post = [];
    foreach ($_POST as $key => $str) {
      if ($key != 'cat_id'){
          $crean_text = htmlspecialchars($str,ENT_QUOTES);
          $post[$key] = $crean_text;
      }else{
        $post[$key] = $str;
      }
    }
    //$postからの処理の説明------フォームから送信されたすべてのデータ（$keyが項目名、$strがその値）を一つずつ順番に取り出します。項目名（$key）が cat_id 以外 の場合と、cat_id の場合 で処理を分けユーザーが入力した値をただの文字列に変換。cat_id だけは無害化せずに、送信された元のデータをそのまま $post['cat_id'] に代入している。cat_idは選択のため！


    //URLの改行があっても1行目だけにする。
    $post['url'] = explode("\n", $post['url'])[0];
    //シリアライズは自動でしてくれる
    //wp_optionにアップサート
    update_option('modal_scroll', $post);

    //保存したら表示させる
    echo '<div class="warp" style="color:#4090ed;">保存しました。</div>';

    }
    
   $modal_settings = get_option('modal_scroll');
   //var_dump($modal_settings);
   
  ?>

<!--ここからインプット-->
<?php require_once 'admin-input.php'?>
<!--onceは1度のみ読み込み。PHPファイルはrequireで読み込む-->
<!--ここからインプット-->
  
  <?php
}


// 記事に挿入
add_filter(
    'the_content',
    function ($content) {
        //設定されてるカテゴリの取得
       $modal_settings = get_option('modal_scroll');
        
        //開いてるページのカテゴリIDを取得
        $categories = get_the_category();
        foreach ($categories as $category) {
            $now_category = $category->term_id; //カテゴリIDの出力;
        }
       //投稿以外のページではカテゴリがないので投稿以外すべてのページでエラーが出ていたためissetで判定を増やした。
        if (isset($now_category, $modal_settings['cat_id']) && in_array($now_category, $modal_settings['cat_id'])){
            //プラグインフォルダのURLを取得(cssはurl参照なので)
            $plugin_url = plugin_dir_url( __FILE__ );
            $html = "<link rel='stylesheet' href='$plugin_url/style.css'>";
            
            // 別ファイルのhtmlを読み込む絶対パス必要 
            $html .= file_get_contents(
                WP_PLUGIN_DIR .'/modal-scroll-img/modal.html'
            );
            // 画像のurlはフルパスを入れる必要がある
            $html = sprintf(
               $html, 
               $modal_settings['url'], 
               $modal_settings['image_path'],
               $modal_settings['top'], 
            );
        }else{
            $html = '';
        }
        return $content . $html; // ← これが記事本文
}       // 対象カテゴリじゃなければ .$html はカラ文字
 , 10 ); 
// 他にあれば10番目に実行される