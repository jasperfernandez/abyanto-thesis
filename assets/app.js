document.querySelectorAll('tr[data-href]').forEach((row) => {
  const openRow = () => {
    window.location.href = row.dataset.href;
  };

  row.addEventListener('click', openRow);
  row.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      openRow();
    }
  });
});

const predictionDialog = document.getElementById('predictionDialog');

if (predictionDialog) {
  predictionDialog.showModal();
}

document.querySelectorAll('form').forEach((form) => {
  form.addEventListener('submit', (event) => {
    const submitter = event.submitter;

    if (!(submitter instanceof HTMLButtonElement)) {
      return;
    }

    if (submitter.name && submitter.value) {
      const actionInput = document.createElement('input');
      actionInput.type = 'hidden';
      actionInput.name = submitter.name;
      actionInput.value = submitter.value;
      form.appendChild(actionInput);
    }

    const loadingText = submitter.dataset.loadingText;

    if (!loadingText) {
      return;
    }

    const label = submitter.querySelector('[data-label]');
    const spinner = submitter.querySelector('[data-spinner]');

    if (label) {
      label.textContent = loadingText;
    }

    if (spinner) {
      spinner.classList.remove('hidden');
    }

    submitter.disabled = true;
    submitter.classList.add('cursor-not-allowed', 'opacity-80');
  });
});
