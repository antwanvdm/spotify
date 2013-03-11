<?php
/**
 * @class SpotifyMetaDataAPI
 * @author Antwan van der Mooren
 * @version 1.0
 * @link https://developer.spotify.com/technologies/web-api/
 */
class SpotifyMetaDataAPI
{
	/**
	 * Endpoint URL
	 * @var string
	 */
	private $spotifyEndpointUrl = "http://ws.spotify.com/@service/1/@method.@format?@parameters";

	/**
	 * Available services as defined in spotify metadata API
	 * @var array
	 */
	private $availableServices = array("search", "lookup");

	/**
	 * Available formats as defined in spotify metadata API
	 * @var array
	 */
	private $availableFormats = array("json", "xml");

	/**
	 * Format for data
	 * @var string
	 */
	private $format = "";

	/**
	 * Initialize object with given format, defaults to json
	 *
	 * @param string $format
	 * @throws Exception
	 */
	public function __construct($format = "json")
	{
		if (in_array($format, $this->availableFormats) === false) {
			throw new Exception("Format is not available within Spotify metadata API");
		}

		$this->format = $format;
	}

	/**
	 * Generic call method for API
	 *
	 * @param $service
	 * @param $arguments
	 * @return mixed
	 * @throws Exception
	 */
	public function __call($service, $arguments)
	{
		if (in_array($service, $this->availableServices) === false) {
			throw new Exception("Service is not available within Spotify metadata API");
		}

		$method = isset($arguments[0]["method"]) ? $arguments[0]["method"] : '';
		$parameters = isset($arguments[0]["parameters"]) ? http_build_query($arguments[0]["parameters"]) : '';

		$url = str_replace(
			array("@service", "@method", "@format", "@parameters"),
			array($service, $method, $this->format, $parameters),
			$this->spotifyEndpointUrl
		);

		return $this->apiCall($url);
	}

	/**
	 * API call with passed URL to the endpoint
	 *
	 * @param $url
	 * @return mixed|object
	 */
	private function apiCall($url)
	{
		$fileContents = file_get_contents($url);
		return $this->format == "json" ? json_decode($fileContents) : simplexml_load_string($fileContents);
	}
}