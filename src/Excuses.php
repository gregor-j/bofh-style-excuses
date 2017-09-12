<?php

/**
 * src/Excuses.php
 *
 * Select a random BOFH-style excuse from a list of excuses.
 *
 * PHP version 5
 *
 * @category Library
 * @package BOFH
 * @author GregorJ
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/gregor-j/bofh-style-excuses
 */

namespace GregorJ\BOFHStyleExcuses;

/**
 * GregorJ\BOFHStyleExcuses\Excuses
 *
 * Select a random BOFH-style excuse from a list of excuses.
 *
 * @category Library
 * @package BOFH
 * @author GregorJ
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/gregor-j/bofh-style-excuses
 */
class Excuses
{

    /**
     * The URL to a list of excuses maintained by Jeff Ballard
     * @var string
     */
    private $url_ballard = "http://pages.cs.wisc.edu/~ballard/bofh/excuses";

    /**
     * The URL to a list of execuses to randomly select from.
     * @var string
     */
    private $url;

    /**
     * The HTTP connection stream context.
     * @var ressource
     */
    private $context;

    /**
     * An array of excuses.
     * @var array
     */
    private $excuses;

    /**
     * The number of excuses in the list of excuses.
     * @var int
     */
    private $excuse_count;

    /**
     * An array of excuses already selected when chosing a random excuse.
     * @var array
     */
    private $already_selected_excuses;

    /**
     * Constructor loading the list of excuses from either a given URL or from
     * the ultimate source - Jeff Ballard's website.
     *
     * @param string $url Optional URL to the list of excuses. Default: null
     */
    public function __construct($url = null)
    {
        if (!is_null($url)) {
            $this->setUrl($url);
        } else {
            //fallback
            $this->url = $this->url_ballard;
        }
        $this->clearExcuses();
    }

    /**
     * Initialize the internal excuse cache.
     *
     * @return object $this
     */
    public function clearExcuses()
    {
        $this->already_selected_excuses = [];
        $this->excuses = null;
        $this->excuse_count = 0;
        return $this;
    }

    /**
     * Set the URL from where to load the list of excuses from.
     *
     * @param string $url The URL to get the excuses from.
     *
     * @return object $this
     * @throws \RuntimeException in case the given string is no URL.
     */
    public function setUrl($url)
    {
        $_url = filter_var($url, FILTER_VALIDATE_URL);
        if (false === $_url) {
            throw new \RuntimeException("The given string is no URL!");
        }
        //don't clear the cache if it's the same url
        if(0 != strcmp($_url, $this->url)) {
            $this->url = $_url;
            $this->clearExcuses();
        }
        return $this;
    }

    /**
     * Fetch the excuses from the defined URL and cache them internally.
     *
     * @return object $this
     * @throws \RuntimeException in case the given URL cannot be opened and read.
     */
    public function fetchExcuses()
    {
        $tmp = \file_get_contents($this->url, false, $this->getContext());
        if (false === $tmp) {
            throw new \RuntimeException(sprintf("Unable to read from %s"),
                                                $this->url);
        }
        $this->excuses = explode(PHP_EOL, $tmp);
        $this->excuse_count = count($this->excuses);
        //reset the list of already used ids
        $this->already_selected_excuses = [];
        return $this;
    }

    /**
     * Determine whether there are any excuses in the list.
     *
     * @return boolean true=there are excuses, false otherwise
     */
    public function hasExcuses()
    {
        if (!is_array($this->excuses)) {
            $this->fetchExcuses();
        }
        return ($this->count() > 0);
    }

    /**
     * Count the number of excuses.
     *
     * @return int The number of excuses cached.
     */
    public function count()
    {
        if (!is_array($this->excuses)) {
            $this->fetchExcuses();
        }
        return $this->excuse_count;
    }

    /**
     * Get an excuse- either a certain one by giving a position parameter,
     * or a random one (default).
     *
     * @param int $pos Optional excuse from position (between and (count-1)). Default: null.
     * @return string The excuse.
     */
    public function get($pos = null)
    {
        if (!is_array($this->excuses)) {
            $this->fetchExcuses();
        }
        if (is_null($pos)) {
            $pos = $this->randomPosition();
        }
        if (isset($this->excuses[$pos])) {
            return $this->excuses[$pos];
        } else {
            return null;
        }
    }

    /**
     * Get a unique random position within the list of excuses that has not been
     * selected before. In case all excuses have already been selected, start
     * over.
     *
     * @return int Random position within the list of excuses.
     */
    public function randomPosition()
    {
        if (!is_array($this->excuses)) {
            $this->fetchExcuses();
        }
        //reset the list of already selected ids in case we used up all possible ids
        if ($this->count() == count($this->already_selected_excuses)) {
            $this->already_selected_excuses = [];
        }
        $max = ($this->count() - 1);
        $return = rand(0, $max);
        //look for new ids that haven't been selected yet
        while (isset($this->already_selected_excuses[$return])) {
            $return = rand(0, $max);
        }
        $this->already_selected_excuses[$return] = true;
        return $return;
    }

    /**
     * Returns the cached context of a HTTP connection.
     * @see createContext() for details.
     *
     * @return ressource The stream context for a HTTP connection or null if
     *         none is necessary.
     */
    public function &getContext()
    {
        if (is_null($this->context)) {
            $this->setContext(getenv('https_proxy'));
        }
        return $this->context;
    }

    /**
     * Creates the context of a HTTP connection.
     * In case there are proxy settings, these proxy settings are returned as
     * stream context.
     *
     * @param string $https_proxy Optional https proxy string.
     * 
     * @return object $this
     */
    public function setContext($https_proxy = null)
    {
        if ($https_proxy) {
            $https_proxy = preg_replace("~^http(s|)~", "tcp", $https_proxy);
            $this->context = stream_context_create([
                'http' => [
                    'proxy' => $https_proxy,
                    'request_fulluri' => true,
                ],
            ]);
        } else {
            $this->context = null;
        }
        return $this;
    }
}
