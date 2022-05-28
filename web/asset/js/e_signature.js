(
    function () {
        var eSignatureApp = angular.module('eSignatureApp', []);

        eSignatureApp.controller('ESignatureAppController', [
            '$scope', function ($scope) {

                var vm = this;

                vm.canvas = document.getElementById('e-signature-tab');
                vm.context = vm.canvas.getContext('2d');
                vm.canvasRect = vm.canvas.getBoundingClientRect();

                vm.mousePosition = {};
                vm.previousMousePosition = {};

                vm.hasDrawingStarted = false;

                $scope.eSignatureUrl = null;

                vm.clearMousePositions = function () {
                    vm.hasDrawingStarted = false;
                    vm.mousePosition = {};
                    vm.previousMousePosition = {};
                }

                vm.setMousePositions = function (event) {
                    if (Object.keys(vm.mousePosition).length === 0) {
                        vm.mousePosition = {
                            x: event.clientX - vm.canvasRect.left,
                            y: event.clientY - vm.canvasRect.top
                        };

                        vm.startDrawing();
                    } else {
                        vm.previousMousePosition = vm.mousePosition;
                        vm.mousePosition = {
                            x: event.clientX - vm.canvasRect.left,
                            y: event.clientY - vm.canvasRect.top
                        };
                    }
                }

                /*vm.showLog = function(eventName) {
                    console.log(
                        'Event : ' + eventName + '. Previous Position (x,y) : '
                        + '(' + vm.previousMousePosition.x + ',' + vm.previousMousePosition.y + ')'
                        + 'Current Position (x,y) : '
                        + '(' + vm.mousePosition.x + ',' + vm.mousePosition.y + ')'
                    );
                }*/

                vm.drawSignature = function () {
                    vm.context.moveTo(vm.previousMousePosition.x, vm.previousMousePosition.y);
                    vm.context.lineTo(vm.mousePosition.x, vm.mousePosition.y);
                    vm.context.stroke();
                }

                vm.startDrawing = function () {
                    vm.context.beginPath();

                    vm.context.strokeStyle = 'black';
                    vm.context.lineWidth = 4;
                }

                $scope.addESignature = function () {
                    $scope.eSignatureUrl = vm.canvas.toDataURL();
                    $scope.clearESignaturePanel();
                }

                $scope.clearESignaturePanel = function () {
                    vm.canvas.width = vm.canvas.width;
                }

                $scope.mouseClickedDown = function (event) {
                    vm.hasDrawingStarted = true;
                    vm.setMousePositions(event);
                }

                $scope.mouseClickedUp = function (event) {
                    vm.clearMousePositions();
                }

                $scope.mouseMoved = function (event) {
                    if (vm.hasDrawingStarted) {
                        vm.setMousePositions(event);
                        vm.drawSignature();
                    }
                }

                $scope.mobileTouched = function (event) {
                    console.log(event);
                }
            }]);
    }
)();