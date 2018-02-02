<?php

class QuoteHelper {
    
    private $quotes_array;
    
    public function add($str, $author) {
        array_push($this->quotes_array, new Quote($str, $author));
    }
    
    public function getRandom() {
        $r = rand(0, count($this->quotes_array)-1);
        return $this->quotes_array[$r]->get();
    }
    
    public function __construct() {
        $this->quotes_array = array();
        $this->add("The Way To Get Started Is To Quit Talking And Begin Doing.", "Walt Disney");
        $this->add("The Pessimist Sees Difficulty In Every Opportunity.<br>The Optimist Sees Opportunity In Every Difficulty.", "Winston Churchill");
        $this->add("Don’t Let Yesterday Take Up Too Much Of Today.", "Will Rogers");
        $this->add("You Learn More From Failure Than From Success.<br>Don’t Let It Stop You.<br>Failure Builds Character.", "WUnknown");
        $this->add("It’s Not Whether You Get Knocked Down, It’s Whether You Get Up.", "Vince Lombardi");
        $this->add("If You Are Working On Something That You Really Care About,<br>You Don’t Have To Be Pushed. The Vision Pulls You.", "Steve Jobs");
        $this->add("We May Encounter Many Defeats But We Must Not Be Defeated.", "Maya Angelou");
        $this->add("My Fake Plants Died Because I Did Not Pretend To Water Them.", "Mitch Hedberg");
        $this->add("The beauty of me is that I’m very rich.", "Donald Trump");
        $this->add("It’s freezing and snowing in New York – we need global warming!", "Donald Trump");
        $this->add("You’re disgusting.", "Donald Trump");
        $this->add("People will stab you in your back and then ask why you're bleeding.", "Tronald Dump");
        $this->add("I never said half the crap people said I did.", "Albert Einstein");
        $this->add("Don't believe everything you read on the internet, just because it's a quote from some famous person.", "Abraham Lincoln");
    }
    
}

class Quote extends Element {
    
    private $str;
    private $author;
    
    public function __construct($str, $author) {
        $this->str = $str;
        $this->author = $author;
    }


    public function get() {
        return "<blockquote class='blockquote'>
                <p class='mb-0' style='font-size: 25px;'>$this->str</p>
                <footer class='blockquote-footer' style='font-size: 20px;'>$this->author</footer>
              </blockquote>";
    }

}