{{--Lanugage bar--}}
<div class="font-name pull-right">

    <?php $setLan = session('language'); ?>
    <span class="">
        <div id="keyboard_selected" class="keyboard btn btn-sm btn-default"><?php echo $setLan; ?></div>

    </span>
    <div id="keyboard_layout" style="display: none;">
        <label class=""><input type="radio" class="keyboar_layout" value="English" onclick="return KeyboardLayoutOptionClick(event);" name="KeyboardLayoutOption" <?php $setLan == 'English' ? 'checked="checked"' : ''; ?>> English</label>
        <label class="font-label"><input type="radio" class="keyboar_layout" value="বিজয়" onclick="return KeyboardLayoutOptionClick(event);" name="KeyboardLayoutOption" <?php $setLan == 'বিজয়' ? 'checked="checked"' : ''; ?> checked> বিজয়</label>
        <label class="font-label"><input type="radio" class="keyboar_layout" value="ফনেটিক" onclick="return KeyboardLayoutOptionClick(event);" name="KeyboardLayoutOption" <?php $setLan == 'ফনেটিক' ? 'checked="checked"' : ''; ?> > ফনেটিক</label>
        <label class="font-label"><input type="radio" class="keyboar_layout" value="অভ্র ফনেটিক" onclick="return KeyboardLayoutOptionClick(event);" name="KeyboardLayoutOption" <?php $setLan == 'অভ্র ফনেটিক' ? 'checked="checked"' : ''; ?> > অভ্র ফনেটিক</label>
        <label class="font-label"><input type="radio" class="keyboar_layout" value="ইউনিজয়" onclick="return KeyboardLayoutOptionClick(event);" name="KeyboardLayoutOption" <?php $setLan == 'ইউনিজয়' ? 'checked="checked"' : ''; ?>> ইউনিজয়</label>
    </div>

</div>
{{--language bar end here--}}

