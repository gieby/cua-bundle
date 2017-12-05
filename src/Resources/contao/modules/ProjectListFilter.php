<?php

class ProjectListFilter extends Module {
    
    /**
	 * Template
	 * @var string
	 */
    protected $strTemplate = 'mod_cua_project_list_filter';

    protected function compile() {

        $status = array('fertiggestellt', 'im-bau','in-planung', 'wettbewerb');
        $statusRef = array('fertiggestellt' => 'Fertiggestellt', 'in-planung' => 'In Planung', 'im-bau' => 'Im Bau', 'wettbewerb' => 'Wettbewerbe');
        $kategorie = array('bildung', 'kultur','gewerbe','sozialbau');
        $kategorieRef = array('bildung' => 'Bildung', 'kultur' => 'Kultur | Sport', 'gewerbe' => 'Büro | Gewerbe', 'sozialbau' => 'Wohnen | Sozialbau');

        // aktuelle URL
        $current_url  = 'http';
        $server_https = $_SERVER["HTTPS"];
        $server_name  = $_SERVER["SERVER_NAME"];
        $request_uri  = $_SERVER["REQUEST_URI"];
        if ($server_https == "on") $current_url .= "s";
        $current_url .= "://";
        $current_url .= $server_name . $request_uri;

        
        $categoryLinks = array();
        $statusLinks = array();

        $categoryLinks['Alle'] = preg_replace('[\&]','?',preg_replace('=[\?\&]kategorie\=[A-Za-z\-]*=is','',$current_url));
        $mobileFilterStatus = preg_replace('[\&]','?',preg_replace('=[\?\&]status\=[A-Za-z\-]*=is','',$current_url));

        if(isset($_GET['kategorie'])) {
            foreach ($kategorie as $value) {
                $categoryLinks[$kategorieRef[$value]] = preg_replace('=kategorie\=[A-Za-z\-]*=is','kategorie=' . $value,$current_url);
            }
        } else {
            foreach ($kategorie as $value) {
                $vorzeichen = isset($_GET['status']) ? '&' : '?';
                $categoryLinks[$kategorieRef[$value]] = $current_url . $vorzeichen . 'kategorie=' . $value;
            }
        }

        if(isset($_GET['status'])) {
            foreach ($status as $value) {
                $statusLinks[$statusRef[$value]] = preg_replace('=status\=[A-Za-z\-]*=is','status=' . $value,$current_url);
            }
        } else {
            foreach ($status as $value) {
                $vorzeichen = isset($_GET['kategorie']) ? '&' : '?';
                $statusLinks[$statusRef[$value]] = $current_url . $vorzeichen . 'status=' . $value;
            }
        }

        $this->Template->categoryLinks = $categoryLinks;
        $this->Template->statusLinks = $statusLinks;
        $this->Template->statusAlle = $mobileFilterStatus;
    }

}