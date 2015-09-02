<?php
defined( 'WPINC' ) or die;

class GD_WordPress_File_Iterator_Filter extends RecursiveFilterIterator {
	public function accept() {

		$filename_regexes = array(
			'#^\.(svn|git|sass-cache|DS_Store)$#',
			'#^\.\.?$#',
		);

		$abspath = ABSPATH;
		if ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) ) {
			$abspath = str_replace( '/', '\\', $abspath );
		}
		$content_dir = WP_CONTENT_DIR;
		if ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) ) {
			$content_dir = str_replace( '/', '\\', $content_dir );
		}

		$pathname_regexes = array(
			// Skip wp-admin and wp-includes
			'#^' . preg_quote( trailingslashit( $abspath ), '#' ) . 'wp-(admin|includes)$#',
			// Skip currently shipped root-level WP files
			'#^' . preg_quote( trailingslashit( $abspath ), '#' ) . '(xmlrpc|wp-(activate|blog-header|comments-post|config-sample|cron|links-opml|load|login|mail|settings|signup|trackback))\.php$#',
			// Skip readme and license
			'#^' . preg_quote( trailingslashit( $abspath ), '#' ) . '(license\.txt|readme\.html)$#',
			// Skip drop-ins
			'#^' . preg_quote( trailingslashit( $content_dir ), '#' ) . '(advanced-cache|object-cache|db)\.php$#',
			// Skip cache
			'#^' . preg_quote( trailingslashit( $content_dir ), '#' ) . '(cache\/page_enhanced)$#',
		);

		// Filter out undseriable files
		foreach ( $filename_regexes as $regex ) {
			if ( preg_match( $regex, $this->current()->getFilename() ) ) {
				return false;
			}
		}

		// Filter out undesirable paths
		foreach ( $pathname_regexes as $regex ) {
			if ( preg_match( $regex, $this->current()->getPathname() ) ) {
				return false;
			}
		}

		// Only folders in wp-content
		if ( $this->current()->isDir() && 0 !== strpos( trailingslashit( $this->current()->getPathname() ), $content_dir ) ) {
			return false;
		}

		// Static files in the root
		if ( $this->current()->isFile() && trailingslashit( ABSPATH ) === trailingslashit( $this->current()->getPath() ) && !preg_match( '/\.(bmp|bz2|css|doc|eot|flv|gif|gz|htm|html|ico|jpeg|jpg|js|less|mp[34]|pdf|png|rar|rtf|swf|tar|tgz|txt|wav|woff|xml|zip)$/i', $this->current()->getFilename() ) ) {
			return false;
		}
		
		if ( substr_count( $this->current()->getPath(), DIRECTORY_SEPARATOR) > 48 )  {
			return false;
		}

		return true;
	}
}
