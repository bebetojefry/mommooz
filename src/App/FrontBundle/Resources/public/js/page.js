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

function openAlert(obj){    
    obj.preventDefault();
    angular.element(obj.target).scope().alert(obj);
    return false;
}

function openPrompt(obj){    
    obj.preventDefault();
    angular.element(obj.target).scope().promptExecuteUrl(obj);
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
        $('.loading').show();
        $('.modal-dialog').attr('class', 'modal-dialog');
        $('.modal-dialog').addClass(obj.target.attributes.modalTitle.value.replace(/ /g,"_").toLowerCase());
        var action = obj.target.attributes.modalUrl;
        action = (action == undefined) ? obj.target.attributes.href : action;
        $http.get(action.value)
        .success(function (response) {
            $scope.processResponse(response);
            $scope.showModal = !$scope.showModal;
        })
        .error(function(response, status){
            $scope.errorhandler(response, status);
        });
        
        action = obj.target.attributes.formAction;
        action = (action == undefined) ? obj.target.attributes.href : action;
        $scope.formAction = action.value;
        $('#modal-form').ajaxForm({
            beforeSubmit: function(arr, $form, options) {
                $('.loading').show();
            },
            success: function(response) {
                $scope.processResponse(response);
            },
            error: function(response, status){
                $scope.errorhandler(response, status);
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
                    $('.loading').show();
                    $http.get(act.value)
                    .success(function (response) {
                        $scope.processResponse(response);
                    })
                    .error(function(response, status){
                        $scope.errorhandler(response, status);
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
    
    $scope.alert = function(obj){
        BootstrapDialog.show({
            title: 'Alert',
            message: obj.target.attributes.alertText.value,
        });
    };
    
    $scope.promptExecuteUrl = function(obj){
        var act = obj.target.attributes.targetUrl;
        act = (act == undefined) ? obj.target.attributes.href : act;
        
        var val = prompt(obj.target.attributes.promptText.value, "");
        if (val != null) {
            $http.get(act.value+'?val='+val)
            .success(function (response) {
                $scope.processResponse(response);
            })
            .error(function(response, status){
                $scope.errorhandler(response, status);
            });
        }
    };
    
    $scope.errorhandler = function(response, status){
        if(status == 304){
            alert(Translator.trans('globals.session.expired'));
        } else if(status == 423){
            if(response.code == 'FOREIGN_INTEGRITY_EXCEPTION'){
                alert('This entity/action is locked, since its connected to entities in other modules.');
            } else {
                alert('This action is locked.');
            }
        } else {
            alert(Translator.trans('globals.error.something'));
        }
        
        location.reload(true);
    }
    
    $scope.processResponse = function(response){
        try { response = jQuery.parseJSON(response); } catch(err) {}
        switch(response.code){
            case 'FORM':
                $('.loading').hide();
                $scope.modalHtml = response.data;
                break;
            case 'FORM_REFRESH':
                $('.loading').hide();
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


$.fn.imageUploader = function( options ) {
    
    var settings = $.extend({
        multiple: true,        
    }, options );
        
    var inputFile = document.createElement("input");
    var preview_box = document.createElement("div");
    $(preview_box).addClass('preview_box');
    inputFile.type = 'file';
    inputFile.id = $(this).attr('id')+'_file';
    inputFile.accept = 'image/*';
    $(this).parent().append(inputFile);
    $(this).parent().append(preview_box);
    $(this).parent().addClass('image_uploader');
    $(this).addClass('real_input');
    
    var that = this;
    if($(this).val() != ''){
        var images = $(this).val().split('<>');
        for(var i = 0; i < images.length; i++){
            var val = images[i].split('|');
            var img_box = document.createElement("div");
            $(img_box).addClass('img_box');
            var img = document.createElement("img");
            img.src = val[1];
            $(img_box).append(img);
            var img_remove_btn = document.createElement("button");
            $(img_remove_btn).attr('index', i);
            $(img_remove_btn).text('X');
            $(img_box).append(img_remove_btn);

            $(img_remove_btn).on('click', function(){
                var index = $(this).attr('index');
                images.splice(index, 1);
                $(that).val(images.join('<>'));
                $(that).parent().find('.preview_box').find('.img_box').eq(index).remove();
            });
            
            $(that).parent().find('.preview_box').append(img_box);
        }
    } else {
        var images = [];
    }
    
    $(inputFile).change(function(){
        if(!settings.multiple && images.length > 0){
            $(that).parent().find('.preview_box').html('');
            images = [];
        }
        
        if (inputFile.files && inputFile.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var img_box = document.createElement("div");
                $(img_box).addClass('img_box');
                var img = document.createElement("img");
                img.src = e.target.result;
                $(img_box).append(img);
                var img_remove_btn = document.createElement("button");
                $(img_remove_btn).attr('index', images.length);
                $(img_remove_btn).text('X');
                $(img_box).append(img_remove_btn);
                
                $(img_remove_btn).on('click', function(){
                    var index = $(this).attr('index');
                    images.splice(index, 1);
                    $(that).parent().find('.preview_box').find('.img_box').eq(index).remove();
                });
                
                $(that).parent().find('.preview_box').append(img_box);
                
                images.push('0|'+e.target.result);
                $(that).val(images.join('<>'));
            }
            reader.readAsDataURL(inputFile.files[0]);
        }
    });
};