<?php

class QuoteHelper {
    
    private $quotes_array;
    
    public function add($str, $author) {
        array_push($this->quotes_array, new Quote($str, $author));
    }
    
    public function __construct() {
        add("The Way Get Started Is To Quit Talking And Begin Doing.", "Walt Disney");
        add("The Pessimist Sees Difficulty In Every Opportunity. The Optimist Sees Opportunity In Every Difficulty.", "Winston Churchill");
        add("Don’t Let Yesterday Take Up Too Much Of Today.", "Will Rogers");
        add("You Learn More From Failure Than From Success. Don’t Let It Stop You. Failure Builds Character.", "WUnknown");
        add("It’s Not Whether You Get Knocked Down, It’s Whether You Get Up.", "Vince Lombardi");
        add("If You Are Working On Something That You Really Care About, You Don’t Have To Be Pushed. The Vision Pulls You.", "Steve Jobs");
        add("We May Encounter Many Defeats But We Must Not Be Defeated.", "Maya Angelou");
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
                <p class='mb-0'>$this->str</p>
                <footer class='blockquote-footer'>$this->author</footer>
              </blockquote>";
    }

}