<?php
class Posterous extends Plugin
{
	public function go()
	{
		foreach ( glob( HABARI_PATH . '/user/plugins/posterousimport/*.xml' ) as $file ) {
			$xml = simplexml_load_file( $file, 'SimpleXMLElement', LIBXML_NOERROR );
			$postdata = array(
				'slug' => $xml->post_name,
				'title' => $xml->title,
				'content' => (string) $xml->encoded[0],
				'user_id' => 1,
				'pubdate' => HabariDateTime::date_create( $xml->pubDate ),
				'status' => Post::status( 'published' ),
				'content_type' => Post::type( 'entry' ),
			);
			$post = Post::create( $postdata );
		}
	}

	public function filter_plugin_config( $actions, $plugin_id )
	{
		if ( $plugin_id == $this->plugin_id() ) {
			$actions[] = _t( 'Execute' );
		}
		return $actions;
	}

	public function action_plugin_ui( $plugin_id, $action )
	{
		if ( $plugin_id == $this->plugin_id() ) {
			switch ( $action ) {
				case _t('Execute') :
					$this->go();
					Utils::redirect( URL::get( 'admin', 'page=plugins' ) );
					break;
			}
		}
	}
}
?>
