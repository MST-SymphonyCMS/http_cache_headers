HTTP Cache Headers
=================

Symphony by default disables browser caching by setting no-cache headers, and expiry dates back in 1980. However this might not always be ideal. In some cases you want to use a third-party caching solution and you want a fine-tuned control of how long pages should be cached, in particular to reduce load on any given server.

This extension lets anyone with basic XSLT knowledge set the right headers. Follow the guide below to set your custom headers.

1. Create a `http-cache-rules` directory inside your workspace.

2. For each `page` that you want to modify the headers create an XSL file named the same way as found in pages.xsl

3. Copy the below into your file as a starting point

	<?xml version="1.0" encoding="UTF-8"?>
	<xsl:stylesheet version="1.0"
		xmlns:xsl="http://www.w3.org/1999/XSL/Transform" >

		<xsl:template match='/'>
			<cache>
				<Cache-Control></Cache-Control>
				<Expires></Expires>
				<Last-Modified></Last-Modified>
			</cache>
		</xsl:template>


	</xsl:stylesheet>

4. Fill each of the provided nodes with the required value to set the headers. For Expires and Last Modified this extension takes any valid date format from [PHP's datetime formats](http://php.net/manual/en/datetime.formats.php). Cache control value has to be a valid Cache Control Header.

Happy HTTP Caching! 