<?php

/*
 this class separates the views from the programming logic [this may need to be changed
 depending on Sarah's feedback about the JSON/JQuery]
*/

class Template {
    // path to the template
    protected $template;
    // array of varibles to pass into the template
    protected $vars = [];

    public function __construct($template)
    {
        $this->template = $template;
    }

    // get the template varibles [uses magic functions]
    public function __get($key) {
        return $this->vars[$key];
    }

    // sets the varibles to be sent to the template [uses magic function]
    public function __set($key, $value) {
        $this->vars[$key] = $value;
    }

    // convert all objects to strings, saves having to do it later..
    public function __toString()
    {
        // extract a string from object
        extract($this->vars);
        // make sure to point it to the right path
        chdir(dirname($this->template));
        // makes the buffer safe... fingers crossed.. 🙃
        ob_start();
        // then it jams the template into the file 👍
        include basename($this->template);
        // and finally we return and empty the buffer
        return ob_get_clean();
    }
}
