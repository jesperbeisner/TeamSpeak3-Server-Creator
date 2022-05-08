const ServerCreateButtons = document.querySelectorAll('.server-create-button');
ServerCreateButtons.forEach((button) => {
  button.addEventListener('click', () => {
    let port = button.dataset.serverPort;
    LoadingButton(button);
    fetch('/teamspeak-create/' + port, {
      method: 'POST',
    })
      .then(response => response.json())
      .then(data => {
        RemoveServerButton(button);

        let text = '<b>TeamSpeak-URL:</b> ' + data['url'] + '<br>';
        text += '<b>Server-Admin-Token:</b> ' + data['server-admin-token'];

        ShowAlertSuccess(text);

        // Change status icon
        let portRow = document.querySelector('#row-' + port);
        let statusIconData = portRow.querySelector('.status-icon-data');
        let onlineIcon = statusIconData.querySelector('.online-icon')
        onlineIcon.classList.remove('d-none');
        let offlineIcon = statusIconData.querySelector('.offline-icon')
        offlineIcon.classList.add('d-none');

        // Change created text
        let createdData = portRow.querySelector('.created-data');
        createdData.innerText = data['created'];
      });
  });
});

const ServerRemoveButtons = document.querySelectorAll('.server-remove-button');
ServerRemoveButtons.forEach((button) => {
  button.addEventListener('click', () => {
    let port = button.dataset.serverPort;
    LoadingButton(button);
    fetch('/teamspeak-remove/' + button.dataset.serverPort, {
      method: 'POST',
    })
      .then(response => response.json())
      .then(data => {
        CreateServerButton(button);

        ShowAlertSuccess('Server with port ' + port + ' removed successfully');

        // Change status icon
        let portRow = document.querySelector('#row-' + port);
        let statusIconData = portRow.querySelector('.status-icon-data');
        let onlineIcon = statusIconData.querySelector('.online-icon')
        onlineIcon.classList.add('d-none');
        let offlineIcon = statusIconData.querySelector('.offline-icon')
        offlineIcon.classList.remove('d-none');

        // Change created text
        let createdData = portRow.querySelector('.created-data');
        createdData.innerText = '-';
      });
  });
});

const LoadingButton = (button) => {
  button.innerHTML = 'Loading... ' + LoadIcon;
  button.classList.remove('btn-success');
  button.classList.remove('btn-danger');
  button.classList.add('btn-primary');
}

const CreateServerButton = (button) => {
  button.innerHTML = 'Create Server';
  button.classList.remove('btn-primary');
  button.classList.remove('btn-primary');
  button.classList.remove('server-remove-button');
  button.classList.add('server-create-button');
  button.classList.add('btn-success');
}

const RemoveServerButton = (button) => {
  button.innerHTML = 'Remove Server';
  button.classList.remove('btn-success');
  button.classList.remove('btn-primary');
  button.classList.remove('server-create-button');
  button.classList.add('server-remove-button');
  button.classList.add('btn-danger');
}

const ShowAlertSuccess = (text) => {
  let alert = document.querySelector('#alert');

  alert.innerHTML = text;
  alert.classList.add('alert-success');
  alert.classList.remove('alert-danger');
  alert.classList.remove('d-none');
}

const LoadIcon = `
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat rotate" viewBox="0 0 16 16">
  <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
  <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
</svg>
`;