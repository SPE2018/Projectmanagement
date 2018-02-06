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
        $this->add("The way to get started is to quit talking and begin doing.", "Walt Disney");
        $this->add("The pessimist sees difficulty in every opportunity.<br>The optimist sees opportunity in every difficulty.", "Winston Churchill");
        $this->add("Don’t let yesterday take up too much of today.", "Will Rogers");
        $this->add("You learn more from failure than from success.<br>Don’t let it stop you. Failure Builds Character.", "Unknown");
        $this->add("It’s not whether you get knocked down, it’s whether you get up.", "Vince Lombardi");
        $this->add("If you are working on something that you really care about,<br>you don’t have to be pushed. The vision pulls you.", "Steve Jobs");
        $this->add("We may encounter many defeats but we must not be defeated.", "Maya Angelou");
        $this->add("My fake plants died because I did not pretend to water them.", "Mitch Hedberg");
        $this->add("The beauty of me is that I’m very rich.", "Donald Trump");
        $this->add("It’s freezing and snowing in New York – we need global warming!", "Donald Trump");
        $this->add("I choose a lazy person to do a hard job. Because a lazy person will find an easy way to do it.", "Bill Gates");
        $this->add("It always seems impossible until it's done.", "Nelson Mandela");
        $this->add("I never said half the crap people said I did.", "Albert Einstein");
        $this->add("Don't believe everything you read on the internet, just because it's a quote from some famous person.", "Abraham Lincoln");
        $this->add("If you don't know where you are going. How can you expect to got there?", "Basil S. Walsh");
        $this->add("Always have a plan, and believe in it. Nothing happens by accident.", "Chuck Knox");
        $this->add("Trying to manage a project without project management is like trying to play a football game without a game plan.", "Katherine Tate");
        $this->add("Do. Or do not. There is no try.", "Yoda");
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
                <footer class='blockquote-footer text-warning text-uppercase' style='font-size: 20px;'>$this->author</footer>
              </blockquote>";
    }

}