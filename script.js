const monthYear = document.getElementById('month-year');
            const calendarDates = document.getElementById('calendar-dates');
            const prevButton = document.getElementById('prev');
            const nextButton = document.getElementById('next');
            const taskList = document.getElementById('task-list');
            const selectedDate = document.getElementById('selected-date');

            let currentDate = new Date(); // Tanggal saat ini
            let selectedDateObj = new Date(currentDate); // Default ke hari ini

            // Fungsi untuk memformat data tugas
            function formatData(data) {
                const formatted = {};

                for (const [key, value] of Object.entries(data)) {
                    const [date, time] = key.split(" ");
                    const [hours, minutes] = time.split(":");
                    const taskWithTime = `${value[0].nama_tugas} - ${hours}:${minutes}`;
                    const status = value[0].status; // status 0 atau 1
                    const taskId = value[0].id_tugas; // Mengambil task ID

                    if (!formatted[date]) {
                        formatted[date] = [];
                    }
                    // Menambahkan id_tugas ke dalam data tugas
                    formatted[date].push({ taskId, taskWithTime, status });
                }

                return formatted;
            }

            let task = {}; // Deklarasikan variabel task secara global

            // Fungsi untuk mengambil data tugas terbaru dari PHP
            function fetchTasksFromServer() {
                const url = 'fetch_tasks.php'; // URL untuk mendapatkan data tugas terbaru
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        // Format data tugas dan simpan ke dalam variabel global task
                        task = formatData(data);
                        updateTaskList(selectedDateObj, task); // Memperbarui daftar tugas dengan data terbaru
                        renderCalendar(currentDate); // Render ulang kalender setelah tugas diperbarui
                    })
                    .catch(error => {
                        console.error('Error fetching tasks:', error);
                    });
            }

            // Fungsi untuk mengambil daftar tugas
            function fetchTasks(date, task) {
                const formattedDate = date.toISOString().split('T')[0];
                return task[formattedDate] || [];
            }

            function updateTaskList(date, task) {
                const formattedDate = date.toISOString().split('T')[0];
                selectedDate.textContent = date.toDateString();

                const tasks = fetchTasks(date, task);
                taskList.innerHTML = tasks.length
                    ? tasks.map((taskData) => {
                        const { taskId, taskWithTime, status } = taskData;
                        const [name, time] = taskWithTime.split(" - ");
                        const taskDeadline = new Date(`${formattedDate}T${time}:00`);
                        const isOverdue = new Date() > taskDeadline;
                        const isChecked = status === '1';

                        return `
                            <li class="p-2 bg-gray-100 rounded ${isOverdue ? 'bg-red-200' : ''} flex justify-between items-center">
                                <div class="flex items-center">
                                    <input type="checkbox" class="mr-2 task-checkbox" data-task-id="${taskId}" ${isChecked ? 'checked' : ''}>
                                    <span class="${isChecked ? 'line-through text-gray-500' : ''}">${name} - ${time}</span>
                                </div>
                                <button class="edit-btn text-blue-500 hover:underline" data-task="${name}" data-time="${time}">Edit</button>
                            </li>
                        `;
                    }).join('')
                    : '<li class="p-2 bg-gray-100 rounded">Tidak ada tugas</li>';

                // Tambahkan event listener untuk tombol edit
                document.querySelectorAll('.edit-btn').forEach((btn) => {
                    btn.addEventListener('click', (event) => {
                        const taskName = event.target.dataset.task;
                        const taskTime = event.target.dataset.time;

                        const newTask = prompt('Edit Tugas:', `${taskName} - ${taskTime}`);
                        if (newTask) {
                            // Proses untuk memperbarui data tugas bisa ditambahkan di sini
                            alert(`Tugas diperbarui ke: ${newTask}`);
                        }
                    });
                });

                document.querySelectorAll('.task-checkbox').forEach((checkbox) => {
                    checkbox.addEventListener('change', (event) => {
                        const taskId = event.target.dataset.taskId; // Ambil task ID dari data-task-id
                        const newStatus = event.target.checked ? 1 : 0; // Tentukan status berdasarkan checkbox

                        if (event.target.checked) {
                            event.target.nextElementSibling.classList.add('line-through', 'text-gray-500');
                        } else {
                            event.target.nextElementSibling.classList.remove('line-through', 'text-gray-500');
                        }

                        // Kirim request untuk memperbarui status tugas menggunakan fetch
                        fetch('update_status.php?task_id=' + taskId + '&status=' + newStatus)
                            .then(response => response.json()) // Mengambil response dalam format JSON
                            .then(data => {
                                if (data.success) {
                                    console.log('Status tugas diperbarui!');
                                } else {
                                    console.log(`Gagal memperbarui status tugas. ${taskId} dan ${newStatus}`);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    });
                });
            }

            // Fungsi untuk merender kalender
            function renderCalendar(date) {
                const year = date.getFullYear();
                const month = date.getMonth();

                monthYear.textContent = date.toLocaleString('default', {
                    month: 'long',
                    year: 'numeric'
                });

                calendarDates.innerHTML = '';

                const firstDay = new Date(year, month, 1).getDay();
                const totalDays = new Date(year, month + 1, 0).getDate();

                const today = new Date();
                const todayDate = today.toISOString().split('T')[0];
                const selectedDateFormatted = selectedDateObj.toISOString().split('T')[0];

                // Tambahkan tombol kosong untuk menyesuaikan hari pertama
                for (let i = 0; i < firstDay; i++) {
                    calendarDates.innerHTML += '<button class="w-full h-12 bg-transparent cursor-not-allowed"></button>';
                }

                // Tambahkan tombol tanggal
                for (let day = 1; day <= totalDays; day++) {
                    const dateObj = new Date(Date.UTC(year, month, day));
                    const formattedDate = dateObj.toISOString().split('T')[0];

                    const hasTask = formattedDate in task;
                    const isToday = formattedDate === todayDate;
                    const isSelected = formattedDate === selectedDateFormatted;

                    const buttonClass = [
                        'w-full h-12 hover:bg-blue-100 rounded-full',
                        isToday ? 'bg-green-600 text-white' : '',
                        isSelected ? 'bg-blue-600 text-white' : '',
                        hasTask ? 'border-2 border-blue-500' : ''
                    ].join(' ');

                    const button = document.createElement('button');
                    button.textContent = day;
                    button.className = buttonClass;
                    button.addEventListener('click', () => {
                        selectedDateObj = dateObj; // Simpan tanggal yang dipilih
                        renderCalendar(currentDate); // Render ulang kalender
                        fetchTasksFromServer(); // Ambil tugas terbaru
                    });

                    calendarDates.appendChild(button);
                }
            }

            // Navigasi bulan sebelumnya
            prevButton.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
            });

            // Navigasi bulan berikutnya
            nextButton.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });

            // Render kalender dan daftar tugas saat halaman dimuat
            renderCalendar(currentDate);
            fetchTasksFromServer(); // Ambil tugas pertama kali