var modal = angular.module('modal', []);

// config override to avoid conflict with twig syntax
modal.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

// html filter with SCE(Script Contextual Escaping)
modal.filter('unsafe', function($sce) {
    return function(val) {
        return $sce.trustAsHtml(val);
    };
});

function openConfirm(obj){    
    obj.preventDefault();
    angular.element(obj.target).scope().confirmExecuteUrl(obj);
    return false;
}

function openModal(obj){    
    obj.preventDefault();
    angular.element(obj.target).scope().toggleModal(obj);
    return false;
}

// Main Controller for modal
modal.controller('MainCtrl', function ($scope, $http) {
    $scope.showModal = false;
    $scope.toggleModal = function(obj){
        $scope.modalTitle = obj.target.attributes.modalTitle.value;
        var action = obj.target.attributes.modalUrl;
        action = (action == undefined) ? obj.target.attributes.href : action;
        $http.get(action.value)
        .success(function (response) {
            $scope.processResponse(response);
            $scope.showModal = !$scope.showModal;
        })
        .error(function(response){
            $scope.errorhandler(response);
        });
        
        action = obj.target.attributes.formAction;
        action = (action == undefined) ? obj.target.attributes.href : action;
        $scope.formAction = action.value;
        $('#modal-form').ajaxForm({
            success: function(response) {
                $scope.processResponse(response);
            },
            error: function(response){
                $scope.errorhandler(response);
            }
        });
    };
    
    $scope.confirmExecuteUrl = function(obj){
        var act = obj.target.attributes.targetUrl;
        act = (act == undefined) ? obj.target.attributes.href : act;
        BootstrapDialog.show({
            title: 'Confirm Action',
            message: obj.target.attributes.cofirmText.value,
            buttons: [{
                label: 'Yes',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    dialog.close();
                    $http.get(act.value)
                    .success(function (response) {
                        $scope.processResponse(response);
                    })
                    .error(function(response){
                        $scope.errorhandler(response);
                    });
                }
                }, {
                label: 'No',
                cssClass: '',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    };
    
    $scope.errorhandler = function(response){
        if(response.status == 304){
            alert(Translator.trans('globals.session.expired'));
        } else {
            alert(Translator.trans('globals.error.something'));
        }
        location.reload(true);
    }
    
    $scope.processResponse = function(response){
        try { response = jQuery.parseJSON(response); } catch(err) {}
        switch(response.code){
            case 'FORM':
                $scope.modalHtml = response.data;
                break;
            case 'FORM_REFRESH':
                $scope.$apply(function() {
                    $scope.modalHtml = response.data;
                });
                break;
            case 'REFRESH':
                location.reload(true);
                break;
            case 'REDIRECT':
                window.location = response.url;
                break;
        }
    };
});

modal.directive('modal', function () {
    return {
      template: '<div class="modal fade">' + 
          '<div class="modal-dialog">' + 
            '<div class="modal-content">' + 
              '<div class="modal-header">' + 
                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' + 
                '<h4 class="modal-title">[[ title ]]</h4>' + 
              '</div>' + 
              '<div class="modal-body" ng-transclude></div>' + 
            '</div>' + 
          '</div>' + 
        '</div>',
      restrict: 'E',
      transclude: true,
      replace:true,
      scope:true,
      link: function postLink(scope, element, attrs) {
            scope.$watch(attrs.visible, function(value){
                scope.title = attrs.title;
                if(value == true)
                    $(element).modal('show');
                else
                    $(element).modal('hide');
                
                $('#modal-form').validator();
            });

            $(element).on('shown.bs.modal', function(){
                scope.$apply(function(){
                    scope.$parent[attrs.visible] = true;
                });
            });

            $(element).on('hidden.bs.modal', function(){
                scope.$apply(function(){
                      scope.$parent[attrs.visible] = false;
                });
            });
        }
    };
});