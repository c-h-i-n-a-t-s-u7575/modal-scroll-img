<style>
  /*設定*/
[name="image_path"],
[name="url"]{
  width: 30rem;}

[name="top"]{
 width: 6rem;
}

[name="cat_id[]"]{
  width: 10rem;
}

.wrap label{
  display: inline-block;
  width: 6rem;
}
select[multiple] option:checked {
  background-color: #1593b6;
  color: white;
 } 
</style>


<div class="wrap">
    <h1>モーダル画像設定</h1>
    <form method="POST" action=""><!--送り先のファイル不要-->

      <p><label>広告画像(フルパス)</label>
      <input type="text" name="image_path" value="<?=$modal_settings['image_path'] ?? '' ?>">
      </p>

      <p><label>スクロールの距離</label>
      <input type="number" name="top" value="<?=$modal_settings['top'] ?? '' ?>">px
      </p>

      <p><label>カテゴリ選択（複数可）</label>
        <select name="cat_id[]" multiple>

        <?php
        $categories = get_categories();

        foreach( $categories as $category ) { 
          if ( isset($modal_settings['cat_id'])&& in_array($category->cat_ID,$modal_settings['cat_id'])){
              $selected = 'selected';
          }else{
              $selected = '';
          }
          ?>
           <option value="<?=$category->cat_ID ?>" 
           <?=$selected?> >
           <?=$category->name ?></option>
        <?php } ?>
        
      </select>
      </p>

      <p><label>広告リンク先</label>
      <textarea name="url"><?=$modal_settings['url'] ??'' ?></textarea>
      </p>

      <p>
      <input type="submit" value="登録">
      </p>
     
    </form>
  </div>