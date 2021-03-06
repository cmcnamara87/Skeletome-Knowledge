function DescriptionCtrl($scope, $http) {

    $scope.defaultDescriptionLength = 500;

    // Setup the default length
    $scope.descriptionLength = $scope.defaultDescriptionLength;
    $scope.model.editedDescription = $scope.description.value;
    $scope.isHidingDescription =  $scope.description.value.length > $scope.defaultDescriptionLength;

    $scope.showDescription = function() {
        $scope.isHidingDescription = false;
        $scope.descriptionLength = $scope.description.safe_value.length + 100;
    }

    $scope.hideDescription = function() {
        $scope.isHidingDescription = true;
        $scope.descriptionLength = $scope.defaultDescriptionLength;
    }

    /* Add / Editing */
    $scope.editDescription = function() {
        $scope.model.editedDescription = $scope.description.value;
        $scope.model.isEditingDescription = true;
    }

    /* Save edited descrption */
    $scope.cancelEditingDescription = function() {
        $scope.model.isEditingDescription = false;
        $scope.model.statementPackage = null;
    }
    $scope.saveEditedDescription = function(newDescription) {

        console.log("saving editied description");
        $scope.model.isEditingDescription = false;

        $scope.description.safe_value = "Loading...";
        $scope.description.isLoading = true;

        var nodeId = 0;
        if(angular.isDefined($scope.master)) {
            nodeId = $scope.master.gene.nid;
        } else {
            nodeId = $scope.model.boneDysplasia.nid;
        }

        // Save the description
        $http.post($scope.description.url, {
            'id': nodeId,
            'description': newDescription
        }).success(function(data) {
            $scope.description.isLoading = false;
            $scope.description.safe_value = data.safe_value;
            $scope.description.value = data.value;
        });

        // Save the statement stuff
        if($scope.model.statementPackage) {
            $http.post($scope.baseUrl + '/?q=ajax/statement/' + $scope.model.statementPackage.nid + '/approve', {
                userIds: $scope.model.statementPackage.users
            }).success(function(statement) {
                jQuery.extend($scope.model.statementPackage.statement, statement);
                angular.forEach($scope.statements, function(existingStatement, index) {
                    if(existingStatement.nid == statement.nid) {
                        $scope.statements.splice(index, 1);
                    }
                })
                $scope.model.statementPackage = null;
            });

        }
    }

    $scope.$watch('model.statementPackage', function(value) {
        console.log("statement saved", value);
    })

}