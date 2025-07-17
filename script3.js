function selectOption(option) {
  document.querySelectorAll('.option').forEach(opt => opt.classList.remove('selected'));
  option.classList.add('selected');
}