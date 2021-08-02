<?php
class BreadCrumbs
{
    private $breadcrumbs = array();
    public function init() {
        
    }
    public function getLinks()
    {
        return $this->breadcrumbs;
    }
    public function addLink($header, $url = null)
    {
        if ($url == null)  {
            $this->breadcrumbs[] = $header;
        } else {
            $this->breadcrumbs[$header] = $url;
        }
    }
    public function clearLinks()
    {
        $this->breadcrumbs = array();
    }
}
?>
