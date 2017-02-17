<div class="col-md-12">
    <div class="row">
        <div class="box">
            <button class="btn btn-primary" ng-click="newGallery()"><i class="fa fa-plus"></i> Add</button>
            <form class="form-horizontal" method="POST" ng-submit="addGallery()" ng-show="showform" name="addform"
                  enctype="multipart/form-data">
                <h3>New Gallery</h3>

                <div class="form-group">
                    <label for="" class="control-label col-md-1">Name</label>

                    <div class="col-md-4">
                        <input type="text" class="form-control" name="name" ng-model="newgallery.name" required=""/>
                    </div>
                    <label for="" class="control-label col-md-1">Description</label>

                    <div class="col-md-4">
                        <textarea class="form-control" name="description" ng-model="newgallery.description"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-1">Photos</label>

                    <div class="col-md-4">
                        <input type="file" name="photos" file-model="files.image" multiple/>
                    </div>
                    <span class="alert alert-danger">Maximum file size 3.5 MB</span>

                    <!--                   for existing image-->
                    <div class="clearfix"></div>
                    <div class="row" style="margin-left: 14px">
                        <div class="col-md-2" ng-repeat="(key,preview) in item_files">
                            <div class="thumbnail" ng-mouseover="showcaption=true" ng-mouseleave="showcaption=false">
                                <div class="caption" ng-show="showcaption">
                                    <div id="content">
                                        <a href="" class="label label-warning" rel="tooltip" title="Show">Show</a>
                                        <a href="" class="label label-danger" rel="tooltip" title="Delete"
                                           confirmed-click="deleteImage(preview)"
                                           ng-confirm-click="Would you like to delete this item?!">Delete</a>
                                    </div>
                                </div>
                                <img src="{{public_url + '/uploads/' + preview.file_name}}" alt="thumbnails">
                            </div>
                        </div>
                    </div>

                    <!--                   for selected -->
                    <div class="clearfix"></div>
                    <div class="row" style="margin-left: 14px">
                        <div class="col-md-2" ng-repeat="image in filespre">
                            <div class="thumbnail">
                                <img src="{{image.url}}" alt="preview"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group text-center">
                    <button class="btn btn-primary" type="submit">Save</button>
                    <button class="btn btn-danger" ng-click="hideForm()">Cancel</button>
                </div>
            </form>
        </div>
        <div class="container" ng-show="show_error">
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-danger alert-dismissable fade in ">
                        <a href="" class="close" data-dismiss="alert" arial-label="close">&times;</a>
                        <h4>following files are not uploaded</h4>

                        <p ng-repeat-start="err in error">{{err}}</p>
                        <hr ng-repeat-end/>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <hr/>
        <div class="row">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3"
                         dir-paginate="gallery in galleries | filter:serach | limitTo:pageSize | itemsPerPage:numPerPage">
                        <div class="cuadro_intro_hover thumbnail" style="background-color:#cccccc;">
                            <p style="text-align:center;">
                                <img class="img-responsive" style="cursor:pointer;"
                                     src="{{public_url + '/uploads/' + gallery.files[0].file_name}}"
                                     alt="{{gallery.name}}"/>
                            </p>

                            <div class="caption">
                                <div class="blur"></div>
                                <div class="caption-text">
                                    <h3 style="border-top:2px solid white; border-bottom:2px solid white; padding:10px;">
                                        {{gallery.name}}</h3>

                                    <p>{{gallery.description}}</p>
                                    <button type="button" style="color: initial;" class="btn btn-default"
                                            data-toggle="modal" data-target="#gallery"
                                            ng-click="showGalleryFiles(gallery)">Open
                                    </button>
                                    <button type="button" style="color: initial;" class="btn btn-default"
                                            ng-click="showForm(gallery)">edit
                                    </button>
                                    <button type="button" style="color: initial;" class="btn btn-default"
                                            confirmed-click="deleteGallery(gallery)"
                                            ng-confirm-click="Would you like to delete this item?!">delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="pull-right">
                        <dir-pagination-controls
                            max-size="10"
                            direction-links="true"
                            boundary-links="true">
                        </dir-pagination-controls>
                    </div>

                <div class="modal" id="gallery" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4>{{galleryfiles.name}}</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2" ng-repeat="file in galleryfiles.files">
                                        <div class="thumbnail img-responsive">
                                            <a href="{{public_url + '/uploads/' + file.file_name}}">
                                                <img src="{{public_url + '/uploads/' + file.file_name}}"
                                                     alt="{{file.file_name}}"/>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="loading" ng-show="loading">
    <div id="loading-image">
        <img src="<?php echo public_url() . 'assets/admin/img/loading.gif' ?>" alt=""/>
        <h4>Please wait...</h4>
    </div>
</div>