<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo $this->get('title');?></title>
        <link rel="icon" type="image/ico" href="<?php echo $this->getBaseUrl(); ?>favicon.ico"/>
        <link href="<?php echo $this->get('media_path'); ?>css/style.css" rel="stylesheet" type="text/css"  media="all" />
    </head>
    <body>
        <?php if (null !== $this->getSessionMessage('error')) { ?>
    <div class="isa_error">
        <i class="fa fa-times-circle"></i>
        <?php echo $this->getSessionMessage('error').'<br/>'; ?>
    </div>
    <?php } ?>
    <?php if (null !== $this->getSessionMessage('warning')) { ?>
    <div class="isa_warning">
        <i class="fa fa-warning"></i>
        <?php echo $this->getSessionMessage('warning').'<br/>'; ?>
    </div>
    <?php } ?>
    <?php if (null !== $this->getSessionMessage('notice')) { ?>
    <div class="isa_info">
        <i class="fa fa-info-circle"></i>
        <?php echo $this->getSessionMessage('notice').'<br/>'; ?>
    </div>
    <?php } ?>
    <?php if (null !== $this->getSessionMessage('success')) { ?>
    <div class="isa_success">
        <i class="fa fa-check"></i>
        <?php echo $this->getSessionMessage('success').'<br/>'; ?>
    </div>
    <?php } ?>