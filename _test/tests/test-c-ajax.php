<?php
/**
 * Class TestUtmDotCodesAjax
 *
 * @package utm.codes
 */

/**
 * Ajax tests, run them whenever you want
 */
class TestUtmDotCodesAjax extends WP_Ajax_UnitTestCase
{
	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * Confirm request for a known valid url returns 200
	 */
	function test_check_url_response_valid() {
		$this->_setRole( 'administrator' );

		$_POST['url'] = 'https://utm.codes';
		$_POST['key'] = wp_create_nonce( UtmDotCodes::REST_NONCE_LABEL );

		try {
			$this->_handleAjax( 'utmdc_check_url_response' );
		}
		catch (WPAjaxDieContinueException $e) {}

		$response = json_decode($this->_last_response);

		$this->assertTrue(
			$response->status == 200,
			'Ajax Error: Status ' . $response->status . ', Msg ' . print_r($response->message, true)
		);
	}

	/**
	 * Confirm request for a known nonexistent url returns 404
	 */
	function test_check_url_response_invalid() {
		$this->_setRole( 'administrator' );

		$_POST['url'] = 'https://utm.codes/asdf';
		$_POST['key'] = wp_create_nonce( UtmDotCodes::REST_NONCE_LABEL );

		try {
			$this->_handleAjax( 'utmdc_check_url_response' );
		}
		catch (WPAjaxDieContinueException $e) {}

		$response = json_decode($this->_last_response);

		$this->assertTrue(
			$response->status == 404,
			'Ajax Error: Status ' . $response->status . ', Msg ' . print_r($response->message, true)
		);
	}

	/**
	 * Confirm request with an invaid nonce returns 500
	 */
	function test_check_url_response_nononce() {
		$this->_setRole( 'administrator' );

		wp_create_nonce( UtmDotCodes::REST_NONCE_LABEL );

		$_POST['url'] = 'https://utm.codes';
		$_POST['key'] = 'oops';

		try {
			$this->_handleAjax( 'utmdc_check_url_response' );
		}
		catch (WPAjaxDieContinueException $e) {}

		$response = json_decode($this->_last_response);

		$this->assertTrue(
			$response->status == 500,
			'Ajax Error: Status ' . $response->status . ', Msg ' . print_r($response->message, true)
		);
	}

	/**
	 * Confirm request with no url returns 500
	 */
	function test_check_url_response_nourl() {
		$this->_setRole( 'administrator' );

		$_POST['url'] = 'oops';
		$_POST['key'] = wp_create_nonce( UtmDotCodes::REST_NONCE_LABEL );

		try {
			$this->_handleAjax( 'utmdc_check_url_response' );
		}
		catch (WPAjaxDieContinueException $e) {}

		$response = json_decode($this->_last_response);

		$this->assertTrue(
			$response->status == 500,
			'Ajax Error: Status ' . $response->status . ', Msg ' . print_r($response->message, true)
		);
	}
}
