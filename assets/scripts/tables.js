const watchColorSchemeForTablesImpl = (fn) => {
    if (!window.matchMedia) {
        return;
    }
    
    const query = window.matchMedia('(prefers-color-scheme: dark)');
    fn(query.matches);        
    query.addEventListener('change', (event) => fn(event.matches));
}

function watchColorSchemeForTables() {
    watchColorSchemeForTablesImpl((isDarkMode) => {
        let tables = document.getElementsByTagName("table");
        for (const table of tables) {
            if (isDarkMode) {
                table.classList.remove('table-light');
                table.classList.add('table-dark');
            } else {
                table.classList.remove('table-dark');
                table.classList.add('table-light');
            }
        }
    })
}