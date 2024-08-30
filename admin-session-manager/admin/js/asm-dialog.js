/**
 * Custom confirmation dialog for the plugin
 * accepts a dom element and options for th dialog
 * prevents opening default confirmation dialog
 */

/**
 * Extend the Element prototype to add the asm_confirm method
 * @param {*} options 
 */
Element.prototype.asmConfirm = function(options) {
    // Default options
    var settings = Object.assign({
        message: 'Are you sure?',
        confirmText: 'Yes',
        cancelText: 'No',
        onConfirm: function() {},
        onCancel: function() {},
    }, options);

    /**
     * Event listener for the element
     */
    this.addEventListener('click', function(e) {
        e.preventDefault();

        // Create the confirmation dialog
        var confirmBox = document.createElement('div');
        confirmBox.className = 'asm-confirm-dialog';

        var message = document.createElement('p');
        message.textContent = settings.message;

        var confirmButton = document.createElement('button');
        confirmButton.className = 'button';
        confirmButton.textContent = settings.confirmText;

        var cancelButton = document.createElement('button');
        cancelButton.className = 'button reset';
        cancelButton.textContent = settings.cancelText;

        confirmBox.appendChild(message);
        confirmBox.appendChild(confirmButton);
        confirmBox.appendChild(cancelButton);
        document.body.appendChild(confirmBox);

        // Style the confirmation box (basic styling)
        confirmBox.style.position = 'fixed';
        confirmBox.style.top = '50%';
        confirmBox.style.left = '50%';
        confirmBox.style.transform = 'translate(-50%, -50%)';
        confirmBox.style.background = '#fff';
        confirmBox.style.padding = '20px';
        confirmBox.style.border = '1px solid #ddd';
        confirmBox.style.zIndex = 999;

        // Confirm button action
        confirmButton.addEventListener('click', function() {
            settings.onConfirm.call(this);
            document.body.removeChild(confirmBox);
        }.bind(this));

        // Cancel button action
        cancelButton.addEventListener('click', function() {
            settings.onCancel.call(this);
            document.body.removeChild(confirmBox);
        }.bind(this));
    }.bind(this));
};
