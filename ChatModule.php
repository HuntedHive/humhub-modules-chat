<?php
class ChatModule extends HWebModule{
 
    /**
     * Inits the Module
     */
    public function init()
    {
        $this->setImport(array(
            'chat.models.*',
        ));
    }
    
}