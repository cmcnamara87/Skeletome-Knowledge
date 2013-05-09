function StatementCtrl($scope, $http) {
    $scope.statementDisplayLimit = 2;

    /**
     * Show the area for statement creation
     */
    $scope.showAddStatement = function() {
        $scope.model.isAddingStatement = true;
    }

    /**
     * Hide the area for statement creation
     * @param newStatement      The statement object to clear
     */
    $scope.cancelStatement = function(newStatement) {
        $scope.model.isAddingStatement = false;
        newStatement = "";
    }

    /**
     * Show the comments for a statement
     * @param statement
     */
    $scope.showComments = function(statement) {
        statement.isShowingComments = !statement.isShowingComments;

        if(angular.isUndefined(statement.comments)) {
            statement.isLoadingComments = true;
            $http.get('?q=ajax/statement/' + statement.nid + '/comments').success(function(data){
                statement.isLoadingComments = false;
                statement.comments = data;
            });
        }
    }

    $scope.deleteCommentFromStatement = function(comment, statement) {
        statement.comments.splice(statement.comments.indexOf(comment), 1);

        $http.get('?q=ajax/statement/' + statement.nid + '/comment/' + comment.cid + '/remove', {
        }).success(function(data) {
            });
    }
    $scope.showEditStatements = function() {
        $scope.isEditingStatements = true;
        angular.forEach($scope.statements, function(statement, index) {
            if(statement.comments && statement.comments.length) {
                statement.showComments = true;
            }
        });
    }
    $scope.hideEditStatements = function() {
        $scope.isEditingStatements = false;
        angular.forEach($scope.statements, function(statement, index) {
            statement.showComments = false;
        });
    }
    $scope.deleteStatement = function(statement) {
        $scope.statements.splice($scope.statements.indexOf(statement), 1);
        $http.get('?q=ajax/statement/' + statement.nid + '/remove', {
        }).success(function(data) {
            });
    }


    /**
     * Adds a comment to a statement
     * @param statement
     * @param comment
     */
    $scope.addComment = function(statement, comment) {

        statement.isLoadingComments = true;

        // Clear the text box
        var commentText = angular.copy(comment);
        statement.newComment = "";

        $http.post('?q=ajax/statement/' + statement.nid + '/comment/add', {
            comment_text: commentText
        }).success(function(data) {
                statement.isLoadingComments = false;
                statement.comments.push(data);
            });
    }
    $scope.cancelComment = function(statement) {
        statement.newComment = "";
        statement.isShowingComments = false;
    }

}