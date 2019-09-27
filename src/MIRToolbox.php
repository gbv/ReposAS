<?php

namespace ReposAS;

use ReposAS\mycore;

class MIRToolbox
{
    public $dbh = false;
    public $config = false;

    public $MyCoReObjectFactory = null;
    public $MyCoReDerivateFactory = null;


    /**
     * prepares database connection and instance variables
     * @param $config config to be used
     * @param $logger optional: set custom logger
     */
    public function __construct($config, $logger = false)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->cache = [];
        $this->lastDerivate = "";
        $this->MyCoReDerivateFactory = new mycore\MyCoReDerivateFactory($config);
        $this->MyCoReObjectFactory = new mycore\MyCoReObjectFactory($config);
    }

    public function addIdentifier(& $reposasLogline)
    {
        $path = $reposasLogline->URL;
        $referer = $reposasLogline->Referer;

        if (preg_match(
            '/\/rsc\/stat\/([^\/]+_[^\/]+_[0-9]{8}).css$/',
            $path,
            $match
        )) {
            // The Fake css download
            //fwrite(STDERR, "Match - fake css:".$path."\n");
            $object = $this->MyCoReObjectFactory->create($match[1]);

            if ($object) {
                $reposasLogline->Identifier = $object->getAllIdentifier();
                $reposasLogline->Subjects[] = "oas:content:counter_abstract";

                return true;
            }
        } elseif (preg_match(
            '/\/receive\/([^\/]+_[^\/]+_[0-9]{8})(;jsessionid.+|$)/',
            $path,
            $match
        )) {
            // Metadatapage docportal and citelink
            //fwrite(STDERR, "Match - receive:".$path."\n");
            $object = $this->MyCoReObjectFactory->create($match[1]);

            if ($object) {
                $reposasLogline->Identifier = $object->getAllIdentifier();
                $reposasLogline->Subjects[] = "oas:content:counter_abstract";

                return true;
            }
        } elseif ($this->config['oldMirLogs'] === true && preg_match(
            '/\/servlets\/solr.+?&rows=1.+?XSL.Style=browse.*/',
            $path,
            $match
        )) {
            die("oldMirLogs not longer supported\n");
        } elseif (preg_match(
            '/\/MCRFileNodeServlet\/([^\/]+_derivate_[0-9]+)\/([^;?]+)(;jsessionid)?([?]view)?.*/',
            $path,
            $match
        )) {
            //Fulltext download
            //fwrite(STDERR, "Match - MCRFileNodeServlet:".$path."\n");
            //fwrite(STDERR, "Derivat (".$match[1].")");

            if (isset($match[3]) && ! (strpos($match[3], "?view") === false)) {  // If intern Dokviewer
                //fwrite(STDERR, "nur Ansicht.\n");

                return false;
            }
            if (strpos(
                $referer,
                "pdf.worker.js"
            ) !== false || strpos($referer, "pdf.min.worker.js") !== false) {
                //fwrite(STDERR, "nur Ansicht(pdfWorker).\n");

                return false;
            }
            $derivateid = $match[1];
            if ($derivateid == $this->lastDerivate) {
                //fwrite(STDERR, "doppeltes Derivate).\n");

                return false;
            }
            $this->lastDerivate = $derivateid;
            $derivate = $this->MyCoReDerivateFactory->create($derivateid);
            if ($derivate == null) {
                return false;
            }
            $maindoc = $derivate->maindoc;
            $filename = urldecode($match[2]);
            //fwrite(STDERR, $maindoc." - ".$filename."\n");

            if ($maindoc == $filename) {
                $reposasLogline->Subjects[] = "oas:content:counter";
                $reposasLogline->Identifier[] = $derivateid;

                // Add objectid
                $objectid = $derivate->objectid;
                //fwrite(STDERR, " ObjectID: ".$objectid."\n");
                $object = $this->MyCoReObjectFactory->create($objectid);
                $reposasLogline->Identifier = array_merge($object->getAllIdentifier(), $reposasLogline->Identifier);

                //Add URN
                $urn = $derivate->urn;
                if ($urn) {
                    $reposasLogline->Identifier[] = $urn;
                }

                return true;
            } else {
                //fwrite(STDERR, "nicht das Hauptdokument\n");
                return false;
            }
        } elseif (preg_match(
            '/\/MCRZipServlet\/([^\/]+_derivate_[0-9]+)(;jsessionid)?.*/',
            $path,
            $match
        )) {
            //fwrite(STDERR, "Match - MCRZipServlet:".$path."\n");
            //fwrite(STDERR, "Derivate (".$match[1].")\n");
            $derivateid = $match[1];
            $reposasLogline->Subjects[] = "oas:content:counter";
            $derivate = $this->MyCoReDerivateFactory->create($derivateid);
            $reposasLogline->Identifier[] = $derivateid;
            $objectid = $derivate->objectid;
            //fwrite(STDERR, "Object:".$objectid."\n");
            $object = $this->MyCoReObjectFactory->create($objectid);
            $reposasLogline->Identifier = array_merge($object->getAllIdentifier(), $reposasLogline->Identifier);
            //Add URN
            $urn = $derivate->urn;
            if ($urn) {
                $reposasLogline->Identifier = $urn;
            }
        } elseif (preg_match(
            '/\/rsc\/pdf\/([^\/]+_derivate_[0-9]+)[?]pages=1-\d+$/',
            $path,
            $match
        )) {
            //fwrite(STDERR, "Match - PDF Download:".$path."\n");
            //fwrite(STDERR, "Derivate (".$match[1].")\n");
            $derivateid = $match[1];
            $reposasLogline->Subjects[] = "oas:content:counter";
            $derivate = $this->MyCoReDerivateFactory->create($derivateid);
            $reposasLogline->Identifier[] = $derivateid;
            $objectid = $derivate->objectid;
            $object = $this->MyCoReObjectFactory->create($objectid);
            $reposasLogline->Identifier = array_merge($object->getAllIdentifier(), $reposasLogline->Identifier);
            //Add URN
            $urn = $derivate->urn;
            if ($urn) {
                $reposasLogline->Identifier = $urn;
            }
        } else {
            return false;
        }
        // TODO a return statement is missing here
    }
}
