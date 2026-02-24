<?php
include(_BASEDIR . "manage/banner_list/item_use_set.php"); //呼叫此單元相關的資料表設定 對應資料表名稱 參數 $data_item_table_name
$element_list = array();
$sql = "select * from 
`" . $data_item_table_name . "`
where 
data_open=1 and
(
    ( 
        data_startdate <> '" . _DateTimeDefaultValue . "' AND 
        data_enddate <> '" . _DateTimeDefaultValue . "' AND 
        (NOW() between data_startdate AND data_enddate) 
    ) OR 
    ( 
        data_startdate = '" . _DateTimeDefaultValue . "' AND 
        data_enddate <> '" . _DateTimeDefaultValue . "' AND 
        (NOW() < data_enddate) 
    )OR
    (
        data_enddate = '" . _DateTimeDefaultValue . "' AND 
        data_startdate <> '" . _DateTimeDefaultValue . "' AND 
        (NOW() > data_startdate)
    ) OR 
    (
        data_startdate = '" . _DateTimeDefaultValue . "' AND 
        data_enddate = '" . _DateTimeDefaultValue . "'
    ) 
) and
data_m_lang = '" . _LANGUAGE . "' and
data_trash=0 
order by 
data_sort desc,
data_id desc";
//select_db(sql, db_conn[, element_list(or null), action_type, pagesize, page, pagenext])
$select_db_action = new select_db($sql, $db_conn, $element_list);
$index_list = $select_db_action->execute();
if (!empty($index_list)) {
?>
    <!--bannnerArea-->
    <script nonce="<?= _CSP_NONCE_CODE ?>">
        function debounce(func, delay) {

            let timer;
            return function(...args) {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    func.apply(this, args);
                }, delay);
            }
        }
    </script>

    <div class="bannerArea">
        <div class="wrap">

            <div class="bannerBox">
                <div class="bannerList slickClsList" id="banner">
                    <?php
                    for ($i = 0; $i < count($index_list); $i++) {
                        foreach ($index_list[$i] as $key => $value) {
                            $$key = $value;
                        }

                        //圖片尺寸 實體路徑
                        $data_list_pic_path = getCropperImage($image_set_filename, $cropper_img_list_set, 'data_list_pic', $data_list_pic, false, false, 'path');
                        if (!empty($data_list_pic_path)) $data_list_pic_size = getimagesize($data_list_pic_path);

                        $data_mobile_pic_path = getCropperImage($image_set_filename, $cropper_img_list_set, 'data_mobile_pic', $data_mobile_pic, false, false, 'path');
                        if (!empty($data_mobile_pic_path)) $data_mobile_pic_size = getimagesize($data_mobile_pic_path);

                        //圖片
                        $data_list_pic = getCropperImage($image_set_filename, $cropper_img_list_set, 'data_list_pic', $data_list_pic);
                        $data_mobile_pic = getCropperImage($image_set_filename, $cropper_img_list_set, 'data_mobile_pic', $data_mobile_pic);

                        // 手機版圖片寬度 先取手機圖片寬度 若無則取預設值 640
                        $mobile_screen_width = !empty($data_mobile_pic_size[0]) ? $data_mobile_pic_size[0] : 640;

                        //是否另開
                        $target = intval($data_popwindow) == 1 ? " target='_blank' " : "";

                        //文字編輯器
                        $data_exp = htmlDecode($data_exp);
                        $data_exp = changeContentPath($data_exp, $http_url_link);

                        if (!empty($data_list_pic)) {
                    ?>
                            <div class="bannerItem slickClsItem">

                                <?php /* if(!empty($data_url) && empty($data_btn_text)) { ?>
                        <a class="bannerLink" href="<?php echo trim($data_url);?>" <?php echo $target;?> title="<?php echo $data_title_front;?>"></a>
                        <?php } */ ?>

                                <div class="Img ">
                                    <?php if (!empty($data_mobile_pic)) { ?>
                                        <picture>

                                            <source data-i="10" data-imgurl="<?php echo $data_list_pic; ?>" class="slickGrid" media="(min-width: <?php echo $mobile_screen_width + 1; ?>px)" srcset="<?php echo $data_list_pic; ?>">

                                            <img
                                                <?php /*src="<?php echo $data_list_pic;?>"*/ ?>
                                                alt="<?php echo trim($data_title_front); ?>"
                                                width="<?php echo $data_mobile_pic_size[0]; ?>"
                                                height="<?php echo $data_mobile_pic_size[1]; ?>"
                                                src="<?php echo $data_mobile_pic; ?>">
                                        </picture>
                                    <?php } else { ?>
                                        <div data-i="10" data-imgurl="<?php echo $data_list_pic; ?>" class="slickGrid"></div>
                                        <img
                                            <?php /*src="<?php echo $data_list_pic;?>"*/ ?>
                                            alt="<?php echo trim($data_title_front); ?>"
                                            width="<?php echo $data_list_pic_size[0]; ?>"
                                            height="<?php echo $data_list_pic_size[1]; ?>"
                                            src="<?php echo $data_list_pic; ?>">
                                    <?php } ?>
                                </div>

                                <div class="Txt">
                                    <?php if (!empty($data_exp)) { ?>
                                        <div class="textBox textEditor">
                                            <?php echo $data_exp; ?>
                                        </div>
                                    <?php } else { ?>
                                        <div class="textBox">
                                            <?php if (!empty($data_title_front)) { ?>
                                                <?php if ($i == 0) { ?>
                                                    <h1 class="title"><?php echo $data_title_front; ?></h1>
                                                <?php } else { ?>
                                                    <div class="title"><?php echo $data_title_front; ?></div>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if (!empty($data_subtitle)) { ?>
                                                <div class="subtitle"><?php echo nl2br($data_subtitle); ?></div>
                                            <?php } ?>
                                            <?php /* if (!empty($data_text)) { ?>
                                                <p class="text"><?php echo $data_text; ?></p>
                                            <?php } */ ?>
                                        </div>
                                    <?php } ?>

                                    <?php if (!empty($data_url)) { ?>
                                        <div class="btnBox ">
                                            <a class="btn bgMain " href="<?php echo trim($data_url); ?>" <?php echo $target; ?> title="<?php echo $data_title_front; ?>">
                                                <?php echo te('了解更多') ?>
                                            </a>
                                        </div>
                                    <?php } ?>

                                    <?php /* if (!empty($data_video_url)) { ?>
                                        <div class="bannerVideo"><a class="videoBtn" href="<?php echo trim($data_video_url); ?>" aria-label="<?php te('播放影片'); ?>"></a></div>
                                    <?php } */ ?>
                                </div>
                                <script nonce="<?= _CSP_NONCE_CODE ?>">
                                    if (document.querySelector('picture')) {
                                        function updateSourceImage() {

                                            document.querySelectorAll('picture source.slickGrid').forEach((item, index) => {

                                                if (window.innerWidth < 640) {

                                                    item.setAttribute('data-imgurl', '<?php echo $data_mobile_pic; ?>');
                                                    item.setAttribute('srcset', '<?php echo $data_mobile_pic; ?>');
                                                } else {
                                                    item.setAttribute('data-imgurl', '<?php echo $data_list_pic; ?>');
                                                    item.setAttribute('srcset', '<?php echo $data_list_pic; ?>');
                                                }
                                            });
                                        }

                                        // 初始更新一次
                                        updateSourceImage();


                                        // 當視窗尺寸改變時更新
                                        window.addEventListener('resize', debounce(updateSourceImage, 200));
                                    }
                                </script>
                            </div>
                    <?php
                        }
                    }
                    ?>

                </div>
            </div>

        </div>
    </div>
    <!--bannerArea end-->


<?php
}
?>