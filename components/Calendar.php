    <style>
        .calendar-day {
            padding: 20px;
            border: 2px solid #e0e0e0;
            text-align: center;
            background: #ffffff;
            color: #333;
            border-radius: 8px;
            margin: 2px;
            flex: 1;
            /* Equal width for all days */
        }

        .workday {
            background: #e3f2fd;
            /* Light blue for workdays */
            border-color: #90caf9;
            /* Darker blue border */
        }

        .weekend {
            background: #fff3e0;
            /* Light golden for weekends */
            border-color: #ffcc80;
            /* Darker golden border */
        }

        .holiday {
            background: #fce4ec;
            /* Light pink for holidays */
            border-color: #f48fb1;
            /* Darker pink border */
        }

        .today,
        .visa-delivery {
            background: #388e3c;
            /* Dark green for today and VISA delivery */
            color: white;
            font-weight: bold;
            border-color: #1b5e20;
            /* Darker green border */
        }

        .calendar-header {
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            border-radius: 8px 8px 0 0;
            display: flex;
        }

        .calendar-header .calendar-day {
            background: transparent;
            border: none;
            color: white;
        }

        .public-holidays {
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .calendar-container {
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            /* box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); */
        }


        .calendar-row {
            display: flex;
            margin: 2px 0;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-12 mb-2">
                <div class="calendar-container">
                    <h2 id="current-date" class="text-center mb-4"></h2>

                    <div id="calendar"></div>
                </div>
            </div>
            <div class="col-12">
                <div class="public-holidays">
                    <h4>Public Holidays</h4>
                    <p>@VISA ARRIVAL</p>
                    <p>We take into account public holidays observed in the country you are traveling to.</p>
                    <h4>Weekends</h4>
                    <p>Embassies are shut on Saturday & Sunday.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function generateCalendar() {
            const now = new Date();
            const year = now.getFullYear();
            const month = now.getMonth();
            const today = now.getDate();

            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startDay = firstDay.getDay();

            const calendar = document.getElementById('calendar');
            calendar.innerHTML = '';

            const headerRow = document.createElement('div');
            headerRow.className = 'calendar-row calendar-header';
            ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(day => {
                const dayCell = document.createElement('div');
                dayCell.className = 'calendar-day';
                dayCell.textContent = day;
                headerRow.appendChild(dayCell);
            });
            calendar.appendChild(headerRow);

            let date = 1;
            for (let i = 0; i < 6; i++) {
                const row = document.createElement('div');
                row.className = 'calendar-row';
                for (let j = 0; j < 7; j++) {
                    const cell = document.createElement('div');
                    cell.className = 'calendar-day';
                    if (i === 0 && j < startDay) {
                        cell.textContent = '';
                    } else if (date > daysInMonth) {
                        cell.textContent = '';
                    } else {
                        cell.textContent = date;
                        if (date === today) {
                            cell.classList.add('today'); // Today's date in dark green
                        } else if (date === 18) { // Example: VISA delivery date (change as needed)
                            cell.classList.add('visa-delivery'); // VISA delivery date in dark green
                        } else if (j === 0 || j === 6) { // Weekends (Sun and Sat)
                            cell.classList.add('weekend');
                        } else { // Workdays (Mon to Fri)
                            cell.classList.add('workday');
                        }
                        // Example: Highlight specific dates as holidays
                        if (date === 15 || date === 20) {
                            cell.classList.remove('workday', 'weekend');
                            cell.classList.add('holiday');
                        }
                        date++;
                    }
                    row.appendChild(cell);
                }
                calendar.appendChild(row);
                if (date > daysInMonth) break;
            }

            const currentDateElement = document.getElementById('current-date');
            currentDateElement.textContent = now.toLocaleString('default', {
                month: 'long'
            }) + ' ' + year + ' at ' + now.toLocaleTimeString();
        }

        generateCalendar();
    </script>