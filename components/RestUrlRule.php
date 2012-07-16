<?php
class RestUrlRule extends CBaseUrlRule {

  public $restControllers;

  /**
   * Creates a URL based on this rule.
   *
   * @param CUrlManager $manager   the manager
   * @param string      $route     the route
   * @param array       $params    list of parameters (name=>value) associated with the route
   * @param string      $ampersand the token separating name-value pairs in the URL.
   *
   * @return mixed the constructed URL. False if this rule does not apply.
   */
  public function createUrl($manager, $route, $params, $ampersand) {
    $paths = explode('/', $route);
    $controller = $paths[0];
    if (array_search($controller, $this->restControllers) === false) return false;
    $url = "api/$controller";
    switch ($paths[1]) {
      case 'restUpdate':
        $url .= '/id/'.$params['id'];
        if (isset($params['var'])) $url .= '/var/'.$params['var'];
        break;
      case 'restList':
        break;
      case 'restView':
        $url .= '/id/'.$params['id'];
        if (isset($params['var'])) $url .= '/var/'.$params['var'];
        if (isset($params['var2'])) $url .= '/var2/'.$params['var2'];
        break;
      case 'restCreate':
        if (isset($params['id'])) $url .= '/id/'.$params['id'];
        break;
      case 'restDelete':
        $url .= '/id/'.$params['id'];
        break;
      default:
        return false;
    }
    return $url;
  }

  /**
   * Parses a URL based on this rule.
   * @param CUrlManager  $manager     the URL manager
   * @param CHttpRequest $request     the request object
   * @param string       $pathInfo    path info part of the URL (URL suffix is already removed based on {@link CUrlManager::urlSuffix})
   * @param string       $rawPathInfo path info that contains the potential URL suffix
   * @return mixed the route that consists of the controller ID and action ID. False if this rule does not apply.
   */
  public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
    $paths = explode('/', $pathInfo);
    if ($paths[0] != "api") return false;
    $controller = $paths[1];
    if (array_search($controller, $this->restControllers) === false) return false;
    switch ($request->getRequestType()) {
      case 'GET':
        if (count($paths) == 2) {
          return $controller ."/restList";
        } else if (count($paths) > 2) {
          $_GET['id'] = $paths[2];
          if (isset($paths[3])) $_GET['var'] = $paths[3];
          if (isset($paths[4])) $_GET['var2'] = $paths[4];
          return $controller ."/restView";
        }
        break;
      case 'PUT':
        if (count($paths) >= 3) {
          $_GET['id'] = $paths[2];
          if (isset($paths[3])) $_GET['var'] = $paths[3];
          return $controller ."/restUpdate";
        }
        break;
      case 'POST':
        if (count($paths) >= 2) {
          if (isset($paths[2])) $_GET['id'] = $paths[2];
          return $controller ."/restCreate";
        }
        break;
      case 'DELETE':
        if (count($paths) == 2) {
          $_GET['id'] = $paths[2];
          return $controller ."/restDelete";
        }
        break;
    }
    return false;  // this rule does not apply
}}
