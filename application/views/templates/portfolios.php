<div class="col-md-12">
   <div class="row">
    <div class="box" style="margin-left: 14px;">
        <button class="btn btn-primary" ng-click="newPortfolio()"><i class="fa fa-plus"></i> Add</button>

        <form class="form-horizontal" method="POST" ng-submit="addPortfolio()" ng-show="showform" name="addform">
            <h3>New Portfolio</h3>
            <div class="form-group">
                <label for="" class="control-label col-md-1">Name</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="name" ng-model="newportfolio.name" required/>
                </div>
                <label for="" class="control-label col-md-1">Type</label>
                <div class="col-md-4">
                    <select name="type" id="type" class="form-control" ng-model="newportfolio.type" required>
                        <option value="" selected disabled>Select</option>
                        <option value="portfolio site">portfolio site</option>
                        <option value="web app">Web Application</option>
                        <option value="standalone">Standalone Application</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-md-1">Description</label>
                <div class="col-md-4">
                    <textarea name="description" id="description" class="form-control" ng-model="newportfolio.description"></textarea>
                </div>
                <label for="" class="control-label col-md-1">link</label>
                <div class="col-md-4">
                        <input type="text" class="form-control" name="link" ng-model="newportfolio.link" pattern="[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?" onblur="checkURL(this);" required/>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-md-1">Display Text</label>
                <div class="col-md-9">
                    <textarea name="displaytext" id="displaytext" class="form-control" ng-model="newportfolio.displaytext"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-md-1">Desktop</label>
                <div class="col-md-3">
                    <input type="file" id="file1" name="file" multiple ng-files="getTheFiles($files)" />
                </div>
                <div class="clearfix"></div>
                <div class=" box">
                    <img src="<?php echo base_url('img/work-1.jpg')?>" alt="thumbnail" class="img-thumbnail" width="140px" height="140px">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-md-1">Mobile</label>
                <div class="col-md-3">
                    <input type="file" name="mobile"/>
                </div>
                <div class="clearfix"></div>
                <div class=" box">
                    <img src="<?php echo base_url('img/work-1.jpg')?>" alt="thumbnail" class="img-thumbnail" width="140px" height="140px">
                </div>
            </div>

            <div class="form-group">
                <button ng-click="uploadFiles()">upload</button>
            </div>

            <div class="form-group text-center">
                <button class="btn btn-primary" type="submit">Save</button>
                <button class="btn btn-danger" type="button" ng-click="hideForm()">Cancel</button>
            </div>
        </form>
        <form class="form-inline">
            <div class="form-group">
                <label >Search</label>
                <input type="text" ng-model="search" class="form-control" placeholder="Search">
            </div>
        </form>
    </div>
   </div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <td>id</td>
            <td>name</td>
            <td>type</td>
            <td>Description</td>
            <td>Display Text</td>
            <td>Link</td>
            <td>action</td>
        </tr>
        </thead>
        <tbody>
<!--        <tr ng-repeat="portfolio in portfolios">-->
        <tr dir-paginate="portfolio in portfolios | filter:search | limitTo:pageSize| itemsPerPage:5">
            <td>{{portfolio.id}}</td>
            <td>{{portfolio.name}}</td>
            <td>{{portfolio.type}}</td>
            <td>{{portfolio.description}}</td>
            <td>{{portfolio.displaytext}}</td>
            <td>{{portfolio.link}}</td>
            <td>
                <div  class="btn-group btn-group-xs" role="group">
                    <button type="button" class="btn btn-info" ng-click="showForm(portfolio)">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button  type="button" class="btn btn-danger" ng-click="deletePortfolio(portfolio)">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <dir-pagination-controls
        max-size="2"
        direction-links="true"
        boundary-links="true" >
    </dir-pagination-controls>
</div>
