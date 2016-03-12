<?php

class Config_Framework_Template extends Config_Framework_App
{
    private $_controller;    
    private $_action;    
    private $_output = '';
    private $_templateData = array();    
    const EXT = '.ash';

    /**
     * 
     * @param type $controller
     * @param type $action
     * @param type $templateData
     */
    public function setView($controller, $action, $templateData = '')
    {
        $this->_controller = $controller;
        $this->_action = $action;
        
        $this->_templateData = $templateData;
    }

    /**
     * public function to 
     * render page and output
     */
    public function render()
    {
        $this->renderHeader();
        $this->renderContent();
        $this->renderFooter();
        $this->_output();        
    }
    
    /**
     * Render the header HTML
     * @return type
     */
    protected function renderHeader()
    {
       if (file_exists($this->getDirectoryPath('Views') . 'layout' . DS . 'header'. self::EXT)) {
            $this->setHeaderParam();
            $this->_output .= $this->_parseTemplate(
                file_get_contents(
                    $this->getDirectoryPath('Views') 
                    . 'layout' . DS 
                    . 'header'.self::EXT
                )
            );
       }
       
       return $this->_output;
    }
    
    /**
     * Render the footer html
     * @return type
     */
    protected function renderFooter()
    {
       if (file_exists($this->getDirectoryPath('Views') . 'layout' . DS . 'footer'. self::EXT)) {
            $this->setHeaderParam();
            $this->_output .= $this->_parseTemplate(
                file_get_contents(
                    $this->getDirectoryPath('Views') 
                    . 'layout' . DS 
                    . 'footer'.self::EXT
                )
            );
       }
       
       return $this->_output;
    }
   
    /**
     * Render the middle content
     * @return type
     */
    protected function renderContent()
    {
       if (file_exists($this->getDirectoryPath('Views') . $this->_controller . DS . $this->_action . self::EXT)) {
            $this->setHeaderParam();
            $this->_output .= $this->_parseTemplate(
                file_get_contents(
                    $this->getDirectoryPath('Views') 
                    . $this->_controller . DS 
                    . $this->_action.self::EXT
                )
            );
       }
       
       return $this->_output;
    }
   
    /**
     * Parse the content
     * veriables
     * @param type $html
     * @return type
     */
    protected function _parseTemplate($html)
    {
       $output = $html;
       
       foreach (parent::$_data as $key => $value) {
           $tagToReplace = "{".$key."}";
           if (isset($value) && !empty($value)) {
               $output = str_replace($tagToReplace, $value, $output);
           }           
       }
       
       return $output;
    }
   
    /**
     * Output all the
     * rendered html
     */
    protected function _output()
    {
       echo $this->_output;
    }
   
    /**
     * set the html
     * parameters
     */
    protected function setHeaderParam()
    {
       $this->set('base_url', $this->getBaseUrl());
       $this->set('style_dir', $this->getCssDir());
    }
}
