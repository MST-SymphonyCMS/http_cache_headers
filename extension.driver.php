<?php

class extension_http_cache_headers extends Extension {
	
	public function update($prev_version){
		return true;
	}
	
	public function install() {
		return true;
	}

	public function getSubscribedDelegates() {
		return array(
			array(
				'page' => '/frontend/',
				'delegate' => 'FrontendOutputPreGenerate',
				'callback' => 'setCacheHeaders'
			),
		);
	}

	public function setCacheHeaders($context){

		//find the path to the XSL file
		preg_match('~\%2Fpages\%2F(.+)"~', $context['xsl'], $matches);

		$XSLTfilename = WORKSPACE . '/http-cache-headers/'. $matches[1];
		if (!file_exists($XSLTfilename)) {
			//no file to update headers exist so return
			return;
		}

		$newXSL = str_replace('%2Fpages%2F', '%2Fhttp-cache-headers%2F', $context['xsl']);

		//uses Symphony XSLT Process
		$XSLProc = new XsltProcess;

		//process requires generated XML and returns valid XML
		$result = $XSLProc->process($context['xml']->generate(), $newXSL);

		//convert the XML into XML Element for easier processes
		$result = XMLElement::convertFromXMLString('cache',$result);

		//put the various headers into the correct formatted variables
		$cacheControl = $result->getChildByName('Cache-Control',0)->getValue();
		$expires = gmdate('D, d M Y H:i:s',strtotime( $result->getChildByName('Expires',0)->getValue()) ) . ' GMT';
		$lastModified = gmdate('D, d M Y H:i:s',strtotime($result->getChildByName('Last-Modified',0)->getValue())) . ' GMT';

		//remove pre-set symphony cache headers
		$context['page']->removeHeaderFromPage('Cache-Control');
		$context['page']->removeHeaderFromPage('Expires');
		$context['page']->removeHeaderFromPage('Last-Modified');
		$context['page']->removeHeaderFromPage('Pragma');

		//append headers with correct values to the page
        $context['page']->addHeaderToPage('Cache-Control', $cacheControl);
        $context['page']->addHeaderToPage('Expires', $expires);
        $context['page']->addHeaderToPage('Last-Modified', $lastModified);

	}
  
}  
?>