<?php echo $this->template('header.php'); ?>
<div class="main">
    <form method="post" action="setconfig">
        <ul class="left-form">
            <h2>Database Configuration :</h2>
            <li>
                <input type="text" required="" name="appConfig[host]" placeholder="Host" tabindex="1" />
                <div class="clear"> </div>
            </li> 
            <li>
                <input type="text" required="" name="appConfig[username]" placeholder="Username" tabindex="2" />
                <div class="clear"> </div>
            </li> 
            <li>
                <input type="text" placeholder="password" tabindex="3" name="appConfig[dbpass]" />
                <div class="clear"> </div>
            </li> 
            <li>
                <input type="text" required="" name="appConfig[dbname]" placeholder="Database Name" tabindex="4" />
                <div class="clear"> </div>
            </li>  
            <input type="submit" value="Submit" onclick="myFunction()" />
            <div class="clear"> </div>
        </ul>
        <ul class="right-form">
            <h3>Application configuration:</h3>
            <div>
                <li>
                    <input type="text" required="" name="appConfig[appPath]" placeholder="Application Path" tabindex="5" value="<?php echo $this->getBaseUrl();?>" />
                </li>
                <li> 
                    <input type="text" required="" name="appConfig[adminPath]" placeholder="Admin Path" tabindex="6" value="<?php echo $this->getBaseUrl(); ?>admin" />
                </li>
            </div>
            <div class="clear"> </div>
        </ul>
        <div class="clear"> </div>
    </form>
</div>
<?php
echo $this->template('footer.php');
