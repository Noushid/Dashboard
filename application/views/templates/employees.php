<div class="col-md-8">
    <div>
        {{error}}
    </div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <td>id</td>
            <td>name</td>
            <td>action</td>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="employee in employees">
            <td>{{employee.id}}</td>
            <td>{{employee.designation}}</td>
            <td>
                <div  class="btn-group btn-group-xs" role="group">
                    <button type="button" class="btn btn-info">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button  type="button" class="btn btn-danger">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <!--<div class="col-md-3">
        <div class="row">
            <div id="widget">
                <div id="outline">
                    <div class="date">
                        <div id="month"></div>
                        <div id="day"></div>
                        <div id="week"></div>
                    </div>
                    <div class="time">
                        <div id="hour"></div>
                        <div id="minut"></div>
                        <div id="second"></div>
                        <div id="date"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
</div>
