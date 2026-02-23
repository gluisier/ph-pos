const getTheme = () => {
	return document.documentElement.getAttribute('data-bs-theme');
}

const getPreferredTheme = () => {
	const theme = localStorage.getItem('theme') || getTheme();
	if (['dark', 'light'].includes(theme)) {
		return theme;
	}

	return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

const setTheme = (theme) => {
	localStorage.setItem('theme', theme);
	document.documentElement.setAttribute('data-bs-theme', theme);
	const themeSwitcher = document.getElementById('ThemeToggler');
	const contentPossible = JSON.parse(themeSwitcher.dataset.contentPossible);
	themeSwitcher.innerText = contentPossible[theme];
}

setTheme(getPreferredTheme());

const toggleTheme = (themeSwitcher) => {
	const tempContent = themeSwitcher.dataset.contentToggle;
	themeSwitcher.dataset.contentToggle = themeSwitcher.innerText;
	themeSwitcher.innerText = tempContent;

	const newTheme = getTheme() === 'light'
		? 'dark'
		: 'light';

	setTheme(newTheme);
}

document.getElementById('ThemeToggler').addEventListener('click', event => {
	toggleTheme(event.target);
});
