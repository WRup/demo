(self["webpackChunk"] = self["webpackChunk"] || []).push([["login"],{

/***/ "./assets/login.js":
/*!*************************!*\
  !*** ./assets/login.js ***!
  \*************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

/* provided dependency */ var $ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
$(function () {
  var usernameEl = $('#username');
  var passwordEl = $('#password'); // in a real application, the user/password should never be hardcoded
  // but for the demo application it's very convenient to do so

  if (!usernameEl.val() || 'jane_admin' === usernameEl.val()) {
    usernameEl.val('jane_admin');
    passwordEl.val('kitten');
  }
});

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ "use strict";
/******/ 
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_jquery_dist_jquery_js"], () => (__webpack_exec__("./assets/login.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvbG9naW4uanMiXSwibmFtZXMiOlsiJCIsInVzZXJuYW1lRWwiLCJwYXNzd29yZEVsIiwidmFsIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7QUFBQUEsQ0FBQyxDQUFDLFlBQVc7QUFDVCxNQUFJQyxVQUFVLEdBQUdELENBQUMsQ0FBQyxXQUFELENBQWxCO0FBQ0EsTUFBSUUsVUFBVSxHQUFHRixDQUFDLENBQUMsV0FBRCxDQUFsQixDQUZTLENBSVQ7QUFDQTs7QUFDQSxNQUFJLENBQUNDLFVBQVUsQ0FBQ0UsR0FBWCxFQUFELElBQXFCLGlCQUFpQkYsVUFBVSxDQUFDRSxHQUFYLEVBQTFDLEVBQTREO0FBQ3hERixjQUFVLENBQUNFLEdBQVgsQ0FBZSxZQUFmO0FBQ0FELGNBQVUsQ0FBQ0MsR0FBWCxDQUFlLFFBQWY7QUFDSDtBQUNKLENBVkEsQ0FBRCxDIiwiZmlsZSI6ImxvZ2luLmpzIiwic291cmNlc0NvbnRlbnQiOlsiJChmdW5jdGlvbigpIHtcclxuICAgIHZhciB1c2VybmFtZUVsID0gJCgnI3VzZXJuYW1lJyk7XHJcbiAgICB2YXIgcGFzc3dvcmRFbCA9ICQoJyNwYXNzd29yZCcpO1xyXG5cclxuICAgIC8vIGluIGEgcmVhbCBhcHBsaWNhdGlvbiwgdGhlIHVzZXIvcGFzc3dvcmQgc2hvdWxkIG5ldmVyIGJlIGhhcmRjb2RlZFxyXG4gICAgLy8gYnV0IGZvciB0aGUgZGVtbyBhcHBsaWNhdGlvbiBpdCdzIHZlcnkgY29udmVuaWVudCB0byBkbyBzb1xyXG4gICAgaWYgKCF1c2VybmFtZUVsLnZhbCgpIHx8ICdqYW5lX2FkbWluJyA9PT0gdXNlcm5hbWVFbC52YWwoKSkge1xyXG4gICAgICAgIHVzZXJuYW1lRWwudmFsKCdqYW5lX2FkbWluJyk7XHJcbiAgICAgICAgcGFzc3dvcmRFbC52YWwoJ2tpdHRlbicpO1xyXG4gICAgfVxyXG59KTtcclxuIl0sInNvdXJjZVJvb3QiOiIifQ==