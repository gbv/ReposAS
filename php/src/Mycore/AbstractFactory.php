<?php

namespace ReposAS\Mycore;

abstract class AbstractFactory
{
    abstract protected function __construct($config);

    abstract protected function create($id);

    protected function getFilePathById($id)
    {
        if (preg_match('/([^\/]+)_([^\/]+)_([0-9]{4})([0-9]{2})[0-9]{2}$/', $id, $match)) {
            $project = $match[1];
            $type = $match[2];
            $dig4 = $match[3];
            $dig2 = $match[4];
        } else {
            return null;
        }
        return "metadata/" . $project . "/" . $type . "/" . $dig4 . "/" . $dig2;
    }

    protected function getDOMByURL($url)
    {
        $doc = new \DOMDocument();
        $count = 0;
        // TODO Why is this developed in this way?
        @$load = $doc->load($url, LIBXML_NOWARNING);
        while ($count < 10 && ! $load) {
            //fwrite(STDERR, "Error: unable to get Data from ".$url.". Try to reconnect(".$count.")\n");
            usleep(2000);
            @$load = $doc->load($url, LIBXML_NOWARNING);
            $count++;
        }
        if (! $load) {
            //throw new Exception("Error: unable to get Data from ".$this->config['url_prefix']);

            return null;
        }
        return $doc;
    }
}
