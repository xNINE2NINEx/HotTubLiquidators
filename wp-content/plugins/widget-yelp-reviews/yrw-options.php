<?php if (isset($title)) { ?>
<div class="form-group">
    <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" placeholder="<?php echo yrw_i('Widget title'); ?>" />
</div>
<?php } ?>

<?php if (isset($business_id)) { ?>
<div class="form-group">
    <input type="text" id="<?php echo $this->get_field_id('business_id'); ?>" name="<?php echo $this->get_field_name('business_id'); ?>" value="<?php echo $business_id; ?>" placeholder="<?php echo yrw_i('Business ID (e.g. benjamin-steakhouse-white-plains)'); ?>" readonly />
</div>
<?php } ?>

<h4 class="rplg-options-toggle"><?php echo yrw_i('Review Options'); ?></h4>
<div class="rplg-options" style="display:none">
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <input class="form-control" type="checkbox" disabled />
            <label><?php echo yrw_i('Try to get more than 3 reviews from Yelp'); ?></label>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <input class="form-control" type="checkbox" disabled />
            <label><?php echo yrw_i('Enable Google Rich Snippet (schema.org)'); ?></label>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <?php echo yrw_i('Pagination'); ?>
            <select class="form-control" disabled >
                <option value=""><?php echo yrw_i('Disabled'); ?></option>
                <option value="10"><?php echo yrw_i('10'); ?></option>
                <option value="5"><?php echo yrw_i('5'); ?></option>
                <option value="4"><?php echo yrw_i('4'); ?></option>
                <option value="3"><?php echo yrw_i('3'); ?></option>
                <option value="2"><?php echo yrw_i('2'); ?></option>
                <option value="1"><?php echo yrw_i('1'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <?php echo yrw_i('Sorting'); ?>
            <select class="form-control" disabled >
                <option value=""><?php echo yrw_i('Default'); ?></option>
                <option value="1"><?php echo yrw_i('Most recent'); ?></option>
                <option value="2"><?php echo yrw_i('Most oldest'); ?></option>
                <option value="3"><?php echo yrw_i('Highest score'); ?></option>
                <option value="4"><?php echo yrw_i('Lowest score'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <?php echo yrw_i('Minimum Review Rating'); ?>
            <select class="form-control" disabled >
                <option value=""><?php echo yrw_i('No filter'); ?></option>
                <option value="5"><?php echo yrw_i('5 Stars'); ?></option>
                <option value="4"><?php echo yrw_i('4 Stars'); ?></option>
                <option value="3"><?php echo yrw_i('3 Stars'); ?></option>
                <option value="2"><?php echo yrw_i('2 Stars'); ?></option>
                <option value="1"><?php echo yrw_i('1 Star'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="rplg-pro"><?php echo yrw_i('These features are available in Yelp Reviews Pro plugin: '); ?>
            <a href="https://richplugins.com/yelp-reviews-pro-wordpress-plugin" target="_blank"><?php echo yrw_i('Upgrade to Pro'); ?></a>
        </div>
    </div>
</div>

<h4 class="rplg-options-toggle"><?php echo yrw_i('Display Options'); ?></h4>
<div class="rplg-options" style="display:none">
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <input class="form-control" type="checkbox" disabled />
            <label><?php echo yrw_i('Hide business photo'); ?></label>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <input class="form-control" type="checkbox" disabled />
            <label><?php echo yrw_i('Hide user avatars'); ?></label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <input id="<?php echo $this->get_field_id('dark_theme'); ?>" name="<?php echo $this->get_field_name('dark_theme'); ?>" class="form-control" type="checkbox" value="1" <?php checked('1', $dark_theme); ?> />
            <label for="<?php echo $this->get_field_id('dark_theme'); ?>"><?php echo yrw_i('Dark theme'); ?></label>
        </div>
    </div>
    <div class="form-group rplg-disabled">
        <div class="col-sm-12">
            <label><?php echo yrw_i('Review limit before \'read more\' link'); ?></label>
            <input class="form-control" type="text" placeholder="for instance: 120"  disabled />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <?php echo yrw_i('Widget theme'); ?>
            <select class="form-control">
                <option value="list"><?php echo yrw_i('Review List'); ?></option>
                <option value="grid" disabled><?php echo yrw_i('Reviews Grid'); ?></option>
                <option value="badge" disabled><?php echo yrw_i('Yelp Badge: right'); ?></option>
                <option value="badge_left" disabled><?php echo yrw_i('Yelp Badge: left'); ?></option>
                <option value="badge_inner" disabled><?php echo yrw_i('Yelp Badge: embed'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="rplg-pro"><?php echo yrw_i('These features are available in Yelp Reviews Pro plugin: '); ?>
            <a href="https://richplugins.com/yelp-reviews-pro-wordpress-plugin" target="_blank"><?php echo yrw_i('Upgrade to Pro'); ?></a>
        </div>
    </div>
</div>

<h4 class="rplg-options-toggle"><?php echo yrw_i('Advance Options'); ?></h4>
<div class="rplg-options" style="display:none">
    <div class="form-group">
        <div class="col-sm-12">
            <input id="<?php echo $this->get_field_id('open_link'); ?>" name="<?php echo $this->get_field_name('open_link'); ?>" class="form-control" type="checkbox" value="1" <?php checked('1', $open_link); ?> />
            <label for="<?php echo $this->get_field_id('open_link'); ?>"><?php echo yrw_i('Open links in new Window'); ?></label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <input id="<?php echo $this->get_field_id('nofollow_link'); ?>" name="<?php echo $this->get_field_name('nofollow_link'); ?>" class="form-control" type="checkbox" value="1" <?php checked('1', $nofollow_link); ?> />
            <label for="<?php echo $this->get_field_id('nofollow_link'); ?>"><?php echo yrw_i('Use no follow links'); ?></label>
        </div>
    </div>
</div>