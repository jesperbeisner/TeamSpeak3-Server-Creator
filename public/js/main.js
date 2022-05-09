const CreateServer = (event) => {
  let button = event.target;
  let port = button.dataset.serverPort;

  CreateLoadingButton(port);

  fetch('/teamspeak-create/' + port, {
    method: 'POST',
  })
    .then(response => response.json())
    .then(data => {
      if (data['status'] === 'success') {
        CreateRemoveServerButton(port);
        ShowAlertSuccess('<b>TeamSpeak-URL:</b> ' + data['url'] + '<br><b>Server-Admin-Token:</b> ' + data['server-admin-token']);
        let portRow = document.querySelector('#row-' + port);
        portRow.querySelector('.created-data').innerText = data['created'];
        DisplayOnlineStatusIcon(portRow);
      } else {
        CreateCreateServerButton(port);
        ShowAlertError(data['message']);
      }
    });
}

const RemoveServer = (event) => {
  let button = event.target;
  let port = button.dataset.serverPort;

  CreateLoadingButton(port);

  fetch('/teamspeak-remove/' + button.dataset.serverPort, {
    method: 'POST',
  })
    .then(response => response.json())
    .then(data => {
      CreateCreateServerButton(port);

      ShowAlertSuccess('Server with port ' + port + ' removed successfully');

      let portRow = document.querySelector('#row-' + port);
      portRow.querySelector('.created-data').innerText = '-';
      DisplayOfflineStatusIcon(portRow);
    });
}

const CreateLoadingButton = (port) => {
  let button = document.createElement("button");

  button.innerHTML = 'Loading... ' + LoadIcon;
  button.classList.add('btn');
  button.classList.add('btn-primary');

  let portRow = document.querySelector('#row-' + port);
  let buttonData = portRow.querySelector('.button-data');

  buttonData.removeChild(buttonData.querySelector('button'));
  buttonData.appendChild(button);
}

const CreateCreateServerButton = (port) => {
  let button = document.createElement("button");
  button.innerText = 'Create Server';

  button.classList.add('btn');
  button.classList.add('btn-success');
  button.classList.add('server-create-button');
  button.dataset.serverPort = port;

  button.addEventListener('click', CreateServer);

  let portRow = document.querySelector('#row-' + port);
  let buttonData = portRow.querySelector('.button-data');

  buttonData.removeChild(buttonData.querySelector('button'));
  buttonData.appendChild(button);
}

const CreateRemoveServerButton = (port) => {
  let button = document.createElement("button");
  button.innerText = 'Remove Server';

  button.classList.add('btn');
  button.classList.add('btn-danger');
  button.classList.add('server-remove-button');
  button.dataset.serverPort = port;

  button.addEventListener('click', RemoveServer);

  let portRow = document.querySelector('#row-' + port);
  let buttonData = portRow.querySelector('.button-data')

  buttonData.removeChild(buttonData.querySelector('button'));
  buttonData.appendChild(button);
}

const ShowAlertSuccess = (text) => {
  let alert = document.querySelector('#alert');

  alert.innerHTML = text;
  alert.classList.add('alert-success');
  alert.classList.remove('alert-danger');
  alert.classList.remove('d-none');
}

const ShowAlertError = (text) => {
  let alert = document.querySelector('#alert');

  alert.innerHTML = text;
  alert.classList.add('alert-danger');
  alert.classList.remove('alert-success');
  alert.classList.remove('d-none');
}

const DisplayOnlineStatusIcon = (portRow) => {
  let statusIconData = portRow.querySelector('.status-icon-data');

  let offlineIcon = statusIconData.querySelector('.offline-icon');
  offlineIcon.classList.add('d-none');

  let onlineIcon = statusIconData.querySelector('.online-icon');
  onlineIcon.classList.remove('d-none');
}

const DisplayOfflineStatusIcon = (portRow) => {
  let statusIconData = portRow.querySelector('.status-icon-data');

  let onlineIcon = statusIconData.querySelector('.online-icon');
  onlineIcon.classList.add('d-none');

  let offlineIcon = statusIconData.querySelector('.offline-icon');
  offlineIcon.classList.remove('d-none');
}

const LoadIcon = `
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat rotate" viewBox="0 0 16 16">
  <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
  <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
</svg>
`;

document.querySelectorAll('.server-create-button').forEach((button) => {
  button.addEventListener('click', CreateServer);
});

document.querySelectorAll('.server-remove-button').forEach((button) => {
  button.addEventListener('click', RemoveServer);
});
