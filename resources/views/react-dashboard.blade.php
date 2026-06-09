<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bioflok KKP - React + Tailwind Prototype</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            light: '#0B1929',
                            dark: '#0f172a',
                        },
                        teal: {
                            light: '#0891B2',
                            dark: '#06b6d4',
                        },
                        navyLight: {
                            light: '#1E3A5F',
                            dark: '#1e293b',
                        },
                        textMain: {
                            light: '#111827',
                            dark: '#F9FAFB',
                        },
                        textMuted: {
                            light: '#4B5563',
                            dark: '#9CA3AF',
                        },
                        bgBody: {
                            light: '#FAFAFB',
                            dark: '#0b0f19',
                        },
                        bgSurface: {
                            light: '#FFFFFF',
                            dark: '#111827',
                        },
                        danger: {
                            DEFAULT: '#EF4444',
                            alert: '#FCA5A5',
                        },
                        success: {
                            DEFAULT: '#10B981',
                            alert: '#6EE7B7',
                        },
                        info: '#3B82F6',
                        warning: '#F59E0B',
                    }
                }
            }
        }
    </script>

    <!-- React, ReactDOM, Babel (CDN) -->
    <script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

    <style>
        /* Base transition for smooth theme switching */
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background-color: #334155;
        }
    </style>
</head>
<body class="antialiased">
    <div id="root"></div>

    <script type="text/babel">
        const { useState, useEffect } = React;

        // --- COMPONENTS ---

        const Card = ({ children, className = "" }) => (
            <div className={`bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 transition-all duration-300 ${className}`}>
                {children}
            </div>
        );

        const KPICard = ({ title, value, unit, icon, colorClass, trend }) => (
            <Card className="p-6 hover:-translate-y-1 hover:shadow-md group">
                <div className="flex justify-between items-start mb-4">
                    <h3 className="text-xs font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider">
                        {title}
                    </h3>
                    <div className={`w-10 h-10 rounded-lg flex items-center justify-center ${colorClass} bg-opacity-10 dark:bg-opacity-20 transition-transform group-hover:scale-110 duration-300`}>
                        <i className={`fa-solid ${icon} text-lg`}></i>
                    </div>
                </div>
                <div className="flex items-baseline gap-2">
                    <span className="text-3xl font-bold text-textMain-light dark:text-textMain-dark">{value}</span>
                    <span className="text-sm font-medium text-textMuted-light dark:text-textMuted-dark">{unit}</span>
                </div>
                {trend && (
                    <div className="mt-4 flex items-center text-xs">
                        <span className={`flex items-center ${trend > 0 ? 'text-success' : 'text-danger'}`}>
                            <i className={`fa-solid fa-arrow-trend-${trend > 0 ? 'up' : 'down'} mr-1`}></i>
                            {Math.abs(trend)}%
                        </span>
                        <span className="text-textMuted-light dark:text-textMuted-dark ml-2">dari bulan lalu</span>
                    </div>
                )}
            </Card>
        );

        const SidebarItem = ({ icon, label, active }) => (
            <a href="#" className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 ${
                active 
                ? 'bg-gradient-to-r from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark text-white shadow-md' 
                : 'text-textMuted-light dark:text-textMuted-dark hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:text-navy-light dark:hover:text-teal-light'
            }`}>
                <i className={`fa-solid ${icon} w-5 text-center ${active ? 'text-white' : ''}`}></i>
                <span className="font-medium">{label}</span>
            </a>
        );

        const Alert = ({ type, title, message }) => {
            const types = {
                success: {
                    bg: 'bg-success/10',
                    border: 'border-success/20',
                    icon: 'fa-check-circle',
                    iconColor: 'text-success',
                    textColor: 'text-success dark:text-success-alert'
                },
                danger: {
                    bg: 'bg-danger/10',
                    border: 'border-danger/20',
                    icon: 'fa-triangle-exclamation',
                    iconColor: 'text-danger',
                    textColor: 'text-danger dark:text-danger-alert'
                }
            };
            const config = types[type];

            return (
                <div className={`flex items-start gap-4 p-4 rounded-xl border ${config.bg} ${config.border} mb-6`}>
                    <i className={`fa-solid ${config.icon} ${config.iconColor} text-xl mt-0.5`}></i>
                    <div>
                        <h4 className={`font-semibold ${config.textColor} mb-1`}>{title}</h4>
                        <p className={`text-sm ${config.textColor} opacity-90`}>{message}</p>
                    </div>
                </div>
            );
        };

        // --- MAIN APP ---

        function App() {
            const [darkMode, setDarkMode] = useState(false);
            const [isSidebarOpen, setIsSidebarOpen] = useState(true);

            // Handle theme initialization
            useEffect(() => {
                if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    setDarkMode(true);
                    document.documentElement.classList.add('dark');
                } else {
                    setDarkMode(false);
                    document.documentElement.classList.remove('dark');
                }
            }, []);

            const toggleTheme = () => {
                const newMode = !darkMode;
                setDarkMode(newMode);
                if (newMode) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            };

            return (
                <div className="min-h-screen bg-bgBody-light dark:bg-bgBody-dark text-textMain-light dark:text-textMain-dark transition-colors duration-300 flex overflow-hidden">
                    
                    {/* Sidebar */}
                    <aside className={`${isSidebarOpen ? 'translate-x-0' : '-translate-x-full'} fixed lg:relative z-40 w-72 h-screen bg-bgSurface-light dark:bg-bgSurface-dark border-r border-gray-200 dark:border-gray-800 transition-transform duration-300 flex flex-col`}>
                        <div className="h-20 flex items-center px-8 border-b border-gray-200 dark:border-gray-800">
                            <div className="flex items-center gap-3">
                                <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-navy-light to-teal-light dark:from-navy-dark dark:to-teal-dark flex items-center justify-center text-white shadow-lg">
                                    <i className="fa-solid fa-fish-fins text-xl"></i>
                                </div>
                                <div>
                                    <h1 className="font-bold text-lg leading-tight bg-clip-text text-transparent bg-gradient-to-r from-navy-light to-teal-light dark:from-teal-dark dark:to-teal-light">
                                        Bioflok KKP
                                    </h1>
                                    <p className="text-xs text-textMuted-light dark:text-textMuted-dark font-medium">Monitoring System</p>
                                </div>
                            </div>
                            <button onClick={() => setIsSidebarOpen(false)} className="lg:hidden ml-auto text-textMuted-light dark:text-textMuted-dark">
                                <i className="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>
                        
                        <div className="flex-1 overflow-y-auto py-6 px-4 space-y-2">
                            <div className="text-xs font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-4 px-4 mt-2">Menu Utama</div>
                            <SidebarItem icon="fa-chart-pie" label="Dashboard" active={true} />
                            <SidebarItem icon="fa-location-dot" label="Data Lokasi KDMP" />
                            <SidebarItem icon="fa-boxes-stacked" label="Monitoring Produksi" />
                            <SidebarItem icon="fa-person-digging" label="Progres Fisik" />
                            
                            <div className="text-xs font-bold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider mb-4 px-4 mt-8">Sistem</div>
                            <SidebarItem icon="fa-users" label="Manajemen User" />
                            <SidebarItem icon="fa-gear" label="Pengaturan" />
                        </div>
                    </aside>

                    {/* Main Content */}
                    <div className="flex-1 flex flex-col h-screen overflow-hidden">
                        {/* Header */}
                        <header className="h-20 bg-bgSurface-light/80 dark:bg-bgSurface-dark/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-6 lg:px-10 z-30">
                            <div className="flex items-center gap-4">
                                <button onClick={() => setIsSidebarOpen(true)} className="lg:hidden text-textMuted-light dark:text-textMuted-dark hover:text-navy-light dark:hover:text-teal-light transition-colors">
                                    <i className="fa-solid fa-bars text-xl"></i>
                                </button>
                                <div>
                                    <h2 className="text-xl font-bold">Dashboard Monitoring</h2>
                                    <p className="text-sm text-textMuted-light dark:text-textMuted-dark hidden sm:block">Ringkasan Produksi & Progres Fisik Nasional</p>
                                </div>
                            </div>

                            <div className="flex items-center gap-4">
                                {/* Theme Toggle */}
                                <button onClick={toggleTheme} className="w-10 h-10 rounded-full flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-textMuted-light dark:text-textMuted-dark hover:text-teal-light dark:hover:text-teal-dark transition-all">
                                    <i className={`fa-solid ${darkMode ? 'fa-sun' : 'fa-moon'} text-lg`}></i>
                                </button>

                                {/* User Profile */}
                                <div className="flex items-center gap-3 pl-4 border-l border-gray-200 dark:border-gray-700">
                                    <div className="text-right hidden sm:block">
                                        <div className="font-semibold text-sm">Administrator</div>
                                        <div className="text-xs text-textMuted-light dark:text-textMuted-dark">Super Admin</div>
                                    </div>
                                    <div className="w-10 h-10 rounded-full bg-gradient-to-tr from-info to-teal-light text-white flex items-center justify-center font-bold shadow-md">
                                        A
                                    </div>
                                </div>
                            </div>
                        </header>

                        {/* Page Content */}
                        <main className="flex-1 overflow-y-auto p-6 lg:p-10 scroll-smooth">
                            <div className="max-w-7xl mx-auto">
                                
                                {/* Welcome Alerts for demonstration */}
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <Alert 
                                        type="success" 
                                        title="Sistem Berjalan Optimal" 
                                        message="Sinkronisasi data produksi terbaru berhasil dilakukan pada 08:30 WIB." 
                                    />
                                    <Alert 
                                        type="danger" 
                                        title="Perhatian Diperlukan" 
                                        message="Terdapat 12 lokasi KDMP yang mengalami underperform bulan ini." 
                                    />
                                </div>

                                {/* KPI Grid */}
                                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-2">
                                    <KPICard 
                                        title="Total Lokasi" 
                                        value="1.245" 
                                        unit="Titik"
                                        icon="fa-location-dot"
                                        colorClass="text-info bg-info"
                                        trend={5.2}
                                    />
                                    <KPICard 
                                        title="Volume Panen" 
                                        value="850.4" 
                                        unit="Ton"
                                        icon="fa-boxes-stacked"
                                        colorClass="text-success bg-success"
                                        trend={12.5}
                                    />
                                    <KPICard 
                                        title="Nilai Produksi" 
                                        value="12.4" 
                                        unit="Miliar Rp"
                                        icon="fa-money-bill-trend-up"
                                        colorClass="text-warning bg-warning"
                                        trend={8.1}
                                    />
                                    <KPICard 
                                        title="Survival Rate" 
                                        value="82.5" 
                                        unit="%"
                                        icon="fa-heart-pulse"
                                        colorClass="text-teal-light dark:text-teal-dark bg-teal-light"
                                        trend={-1.2}
                                    />
                                </div>

                                {/* Content Grid */}
                                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                                    
                                    {/* Chart Placeholder 1 */}
                                    <Card className="lg:col-span-2 p-6 flex flex-col">
                                        <div className="flex justify-between items-center mb-6">
                                            <h3 className="font-bold text-lg flex items-center gap-2">
                                                <i className="fa-solid fa-chart-line text-teal-light dark:text-teal-dark"></i>
                                                Tren Produksi Bulanan
                                            </h3>
                                            <button className="text-sm text-info hover:underline font-medium">Lihat Detail</button>
                                        </div>
                                        <div className="flex-1 bg-gray-50 dark:bg-gray-800/50 rounded-xl flex items-center justify-center border border-dashed border-gray-300 dark:border-gray-700 min-h-[300px]">
                                            <div className="text-center text-textMuted-light dark:text-textMuted-dark">
                                                <i className="fa-solid fa-chart-bar text-4xl mb-3 opacity-50"></i>
                                                <p className="font-medium">Area Chart Visualisasi</p>
                                                <p className="text-xs mt-1">(Integrasi Chart.js / ApexCharts)</p>
                                            </div>
                                        </div>
                                    </Card>

                                    {/* List Component */}
                                    <Card className="p-6">
                                        <div className="flex justify-between items-center mb-6">
                                            <h3 className="font-bold text-lg flex items-center gap-2">
                                                <i className="fa-solid fa-arrow-trend-up text-success"></i>
                                                Top Performa KDMP
                                            </h3>
                                        </div>
                                        <div className="space-y-4">
                                            {[1, 2, 3, 4, 5].map((item) => (
                                                <div key={item} className="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors border border-transparent hover:border-gray-100 dark:hover:border-gray-800">
                                                    <div className={`w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm ${item <= 3 ? 'bg-teal-light/20 text-teal-light dark:text-teal-dark' : 'bg-gray-100 dark:bg-gray-800 text-textMuted-light dark:text-textMuted-dark'}`}>
                                                        {item}
                                                    </div>
                                                    <div className="flex-1">
                                                        <h4 className="text-sm font-semibold">Pokdakan Maju Bersama</h4>
                                                        <p className="text-xs text-textMuted-light dark:text-textMuted-dark">Kab. Demak, Jawa Tengah</p>
                                                    </div>
                                                    <div className="text-right">
                                                        <div className="text-sm font-bold text-teal-light dark:text-teal-dark">2.4 Ton</div>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                        <button className="w-full mt-6 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                            Lihat Semua Peringkat
                                        </button>
                                    </Card>
                                </div>
                            </div>
                        </main>
                    </div>

                    {/* Mobile Sidebar Overlay */}
                    {isSidebarOpen && (
                        <div 
                            className="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 lg:hidden"
                            onClick={() => setIsSidebarOpen(false)}
                        ></div>
                    )}
                </div>
            );
        }

        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(<App />);
    </script>
</body>
</html>
