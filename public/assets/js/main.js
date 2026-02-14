/**
 * Main Frontend Logic
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Comedor Universitario Frontend Initialized');
});

/**
 * Initialize Real-time Table Search
 * @param {string} inputId - ID of the search input field
 * @param {string} tableId - ID of the table to filter
 */
function initTableSearch(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);

    if (!input || !table) return;

    input.addEventListener('keyup', function () {
        try {
            const filter = input.value.toLowerCase();
            const rows = table.getElementsByTagName('tr');

            // Start from 1 to skip header row
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let shouldShow = false;

                if (cells.length > 0) {
                    const nameCell = cells[0];
                    if (nameCell) {
                        const txtValue = nameCell.textContent || nameCell.innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            shouldShow = true;
                        }
                    }
                }

                row.style.display = shouldShow ? '' : 'none';
            }
        } catch (e) {
            console.error('Error in search filter:', e);
        }
    });
}
