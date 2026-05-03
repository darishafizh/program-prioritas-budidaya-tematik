@extends('layouts.app')

@section('content')
{{-- ═══════════ HEADER ═══════════ --}}
<div class="dash-header">
    <div>
        <h2 class="dash-title">Dashboard Monitoring</h2>
        <p class="dash-subtitle">Ringkasan Produksi & Progres Fisik — Tahun {{ $filterTahun }}</p>
    </div>
    <form method="GET" action="{{ route('dashboard') }}" class="dash-filter" id="fForm">
        <select name="tahun" class="dash-fsel" onchange="this.form.submit()">
            @foreach($tahunList as $t)
            <option value="{{ $t }}" {{ $filterTahun == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        <span class="dash-fdiv"></span>
        <select name="provinsi" class="dash-fsel" onchange="this.form.submit()">
            <option value="">Semua Provinsi</option>
            @foreach($provinsiList as $p)
            <option value="{{ $p }}" {{ $filterProvinsi == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
        <span class="dash-fdiv"></span>
        <select name="komoditas" class="dash-fsel" onchange="this.form.submit()">
            <option value="">Semua Komoditas</option>
            @foreach($komoditasList as $k)
            <option value="{{ $k }}" {{ $filterKomoditas == $k ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>
        @if($filterProvinsi || $filterKomoditas)
        <a href="{{ route('dashboard') }}" class="dash-freset"><i class="fa-solid fa-xmark"></i></a>
        @endif
    </form>
</div>

{{-- ═══════════ KPI CARDS 2×3 ═══════════ --}}
<div class="kpi-grid">
    <div class="kc kc-blue">
        <div class="kc-ico"><i class="fa-solid fa-location-dot"></i></div>
        <div class="kc-body"><span class="kc-val">{{ number_format($totalLokasi) }}</span><span class="kc-lbl">Total Lokasi KDMP</span></div>
    </div>
    <div class="kc kc-emerald">
        <div class="kc-ico"><i class="fa-solid fa-boxes-stacked"></i></div>
        <div class="kc-body"><span class="kc-val">{{ number_format($totalProduksi, 0, ',', '.') }} <small>kg</small></span><span class="kc-lbl">Volume Panen</span></div>
    </div>
    <div class="kc kc-amber">
        <div class="kc-ico"><i class="fa-solid fa-money-bill-trend-up"></i></div>
        <div class="kc-body"><span class="kc-val">Rp {{ number_format($totalNilaiProduksi / 1000000, 1, ',', '.') }}M</span><span class="kc-lbl">Nilai Produksi</span></div>
    </div>
    <div class="kc kc-cyan">
        <div class="kc-ico"><i class="fa-solid fa-heart-pulse"></i></div>
        <div class="kc-body"><span class="kc-val">{{ number_format($avgSR, 1) }}%</span><span class="kc-lbl">Rata-rata Survival Rate</span></div>
    </div>
    <div class="kc kc-violet">
        <div class="kc-ico"><i class="fa-solid fa-helmet-safety"></i></div>
        <div class="kc-body"><span class="kc-val">{{ $avgProgresFisik }}%</span><span class="kc-lbl">Rata-rata Progres Fisik</span></div>
    </div>
    <div class="kc kc-teal">
        <div class="kc-ico"><i class="fa-solid fa-water"></i></div>
        <div class="kc-body"><span class="kc-val">{{ $utilisasi }}%</span><span class="kc-lbl">Utilisasi Kolam Aktif</span></div>
    </div>
</div>

{{-- ═══════════ MAP (full-width) ═══════════ --}}
<div class="map-card">
    <div class="map-head">
        <h6><i class="fa-solid fa-map-location-dot"></i> Peta Infografis Lokasi KDMP</h6>
        <div class="map-leg">
            <span><i class="fa-solid fa-circle" style="color:#10B981"></i> On Track</span>
            <span><i class="fa-solid fa-circle" style="color:#EF4444"></i> Underperformed</span>
            <span><i class="fa-solid fa-circle" style="color:#94A3B8"></i> Belum Lapor</span>
        </div>
    </div>
    <div class="map-body" style="position: relative;">
        <div id="mapEl" style="height: 560px; width: 100%; z-index: 1; border-radius: 0 0 16px 16px;"></div>
        
        <div class="map-overlay-card" id="mapOverlay">
            <button type="button" class="mo-close" onclick="closeMapOverlay()"><i class="fa-solid fa-xmark"></i></button>
            <div class="ms-content" id="msContent">
                <div class="ms-header" style="padding-right: 45px;">
                    <div>
                        <div class="ms-no" id="msNo"></div>
                        <h6 id="msName"></h6>
                        <div class="ms-loc" id="msLoc"></div>
                    </div>
                    <span class="ms-status" id="msStatus"></span>
                </div>
                <div class="ms-section">
                    <div class="ms-stitle">Komoditas</div>
                    <div class="ms-val" id="msKom">-</div>
                </div>
                <div class="ms-section">
                    <div class="ms-stitle">Data Produksi</div>
                    <div class="ms-row"><span>Volume Panen</span><strong id="msVol">-</strong></div>
                    <div class="ms-row"><span>Nilai Produksi</span><strong id="msNilai">-</strong></div>
                    <div class="ms-row"><span>Biaya Operasional</span><strong id="msBiaya">-</strong></div>
                    <div class="ms-row"><span>Survival Rate</span><strong id="msSR">-</strong></div>
                    <div class="ms-row"><span>Kolam Aktif</span><strong id="msKolam">-</strong></div>
                </div>
                <div class="ms-section" id="msKendalaSection" style="display:none">
                    <div class="ms-stitle">Kendala</div>
                    <div class="ms-kendala" id="msKendala"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════ ROW: RADAR + BAR ═══════════ --}}
{{-- ═══════════ ROW: CHARTS (RADAR, KOMODITAS, BAR PROVINSI) ═══════════ --}}
<div class="grid-3-3-6">
    <div class="panel">
        <div class="ph"><h6><i class="fa-solid fa-trowel-bricks"></i> Progres Fisik</h6></div>
        <div class="pb chart-c" style="padding-top:8px"><canvas id="cRadar" height="260"></canvas></div>
    </div>
    <div class="panel">
        <div class="ph"><h6><i class="fa-solid fa-fish-fins"></i> Komoditas</h6></div>
        <div class="pb chart-c" style="padding-top:8px"><canvas id="cKom" height="260"></canvas></div>
    </div>
    <div class="panel">
        <div class="ph"><h6><i class="fa-solid fa-chart-column"></i> Produksi Provinsi (kg)</h6></div>
        <div class="pb" style="height:260px"><canvas id="cBar"></canvas></div>
    </div>
</div>

{{-- ═══════════ ROW: TREND PRODUKSI (FULL WIDTH) ═══════════ --}}
<div class="grid-full">
    <div class="panel">
        <div class="ph" style="align-items: center;">
            <h6><i class="fa-solid fa-chart-line"></i> Tren Produksi</h6>
            <form method="GET" action="{{ route('dashboard') }}" id="trendForm" style="margin:0">
                <input type="hidden" name="provinsi" value="{{ $filterProvinsi }}">
                <input type="hidden" name="komoditas" value="{{ $filterKomoditas }}">
                <select name="tahun" class="dash-fsel" style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:20px;padding:4px 12px;" onchange="document.getElementById('trendForm').submit()">
                    @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $filterTahun == $t ? 'selected' : '' }}>Tahun {{ $t }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="pb" style="height:350px"><canvas id="cTrend"></canvas></div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
/* ═══ Header ═══ */
.dash-header{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:22px}
.dash-title{font-size:1.6rem;font-weight:800;margin:0;background:linear-gradient(135deg,var(--kkp-navy),var(--kkp-teal));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
[data-theme="dark"] .dash-title{background:linear-gradient(135deg,#67e8f9,#06b6d4);-webkit-background-clip:text;background-clip:text}
.dash-subtitle{font-size:.82rem;color:var(--gray-500);margin:4px 0 0}
.dash-filter{display:flex;align-items:center;gap:6px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:40px;padding:6px 14px;box-shadow:var(--shadow-sm)}
.dash-fsel{border:none;background:transparent;font-size:.82rem;font-weight:600;color:var(--text-primary);padding:4px 6px;outline:none;cursor:pointer}
.dash-fsel option{background:var(--bg-surface)}
.dash-fdiv{width:1px;height:18px;background:var(--border-color)}
.dash-freset{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#FEE2E2;color:#DC2626;font-size:.75rem;text-decoration:none;transition:all .2s}
.dash-freset:hover{background:#FECACA;transform:scale(1.1)}

/* ═══ KPI Grid ═══ */
.kpi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:22px}
@media(max-width:768px){.kpi-grid{grid-template-columns:repeat(2,1fr)}}
.kc{display:flex;align-items:center;gap:14px;padding:20px;border-radius:16px;background:var(--bg-surface);border:1px solid var(--border-color);box-shadow:var(--shadow-sm);transition:transform .25s,box-shadow .25s}
.kc:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg)}
.kc-ico{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0}
.kc-body{display:flex;flex-direction:column}
.kc-val{font-size:1.35rem;font-weight:800;line-height:1.2;color:var(--text-primary)}
.kc-val small{font-size:.8rem;font-weight:600;color:var(--gray-500)}
.kc-lbl{font-size:.72rem;font-weight:600;color:var(--gray-500);text-transform:uppercase;letter-spacing:.4px;margin-top:3px}
.kc-blue .kc-ico{background:rgba(59,130,246,.12);color:#3B82F6}
.kc-emerald .kc-ico{background:rgba(16,185,129,.12);color:#10B981}
.kc-amber .kc-ico{background:rgba(245,158,11,.12);color:#F59E0B}
.kc-cyan .kc-ico{background:rgba(6,182,212,.12);color:#06B6D4}
.kc-violet .kc-ico{background:rgba(139,92,246,.12);color:#8B5CF6}
.kc-teal .kc-ico{background:rgba(13,148,136,.12);color:#0D9488}

/* ═══ Map Card ═══ */
.map-card{background:var(--bg-surface);border:1px solid var(--border-color);border-radius:16px;box-shadow:var(--shadow-sm);overflow:hidden;margin-bottom:18px}
.map-head{padding:16px 20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;border-bottom:1px solid var(--border-color)}
.map-head h6{margin:0;font-size:.88rem;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:8px}
.map-head h6 i{color:var(--kkp-teal)}
.map-leg{display:flex;gap:14px;flex-wrap:wrap;font-size:.72rem;font-weight:600;color:var(--gray-500)}
.map-leg i{font-size:7px;vertical-align:middle}
.map-body{position:relative}
.map-overlay-card{position:absolute;top:20px;right:20px;width:320px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:16px;box-shadow:var(--shadow-lg);z-index:1000;max-height:calc(100% - 40px);overflow-y:auto;opacity:0;visibility:hidden;transform:translateX(20px);transition:all .3s ease;padding:0}
.map-overlay-card.show{opacity:1;visibility:visible;transform:translateX(0)}
.mo-close{position:absolute;top:14px;right:14px;background:rgba(0,0,0,.05);border:none;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--gray-500);z-index:10;transition:all .2s}
.mo-close:hover{background:#FEE2E2;color:#EF4444}
[data-theme="dark"] .mo-close{background:rgba(255,255,255,.1);color:var(--gray-400)}
[data-theme="dark"] .mo-close:hover{background:rgba(239,68,68,.2);color:#FCA5A5}
@media(max-width:768px){.map-overlay-card{top:auto;bottom:20px;right:15px;left:15px;width:auto;transform:translateY(20px)}.map-overlay-card.show{transform:translateY(0)}}

/* Sidebar content */
.ms-content{padding:0}
.ms-header{padding:16px 18px;border-bottom:1px solid var(--border-color);display:flex;justify-content:space-between;align-items:flex-start;gap:10px}
.ms-header h6{margin:0;font-size:.92rem;font-weight:700;color:var(--text-primary);line-height:1.3}
.ms-no{font-size:.65rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px}
.ms-loc{font-size:.75rem;color:var(--gray-500);margin-top:2px}
.ms-status{display:inline-block;padding:3px 10px;border-radius:20px;font-size:.65rem;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:.5px;white-space:nowrap;flex-shrink:0;margin-top:2px}
.ms-section{padding:12px 18px;border-bottom:1px solid var(--border-color)}
.ms-section:last-child{border-bottom:none}
.ms-stitle{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--gray-400);margin-bottom:6px}
.ms-val{font-size:.85rem;font-weight:700;color:var(--text-primary)}
.ms-row{display:flex;justify-content:space-between;align-items:center;padding:4px 0;font-size:.8rem}
.ms-row span{color:var(--gray-500);font-weight:500}
.ms-row strong{color:var(--text-primary);font-weight:700}
.ms-kendala{font-size:.8rem;color:var(--text-secondary);line-height:1.5}

/* Dark mode map */
[data-theme="dark"] .map-card,[data-theme="dark"] .map-overlay-card{background:var(--bg-surface);border-color:var(--border-color)}
[data-theme="dark"] .leaflet-tile-pane{filter:invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%)}

/* ═══ Panels ═══ */
.panel{background:var(--bg-surface);border:1px solid var(--border-color);border-radius:16px;box-shadow:var(--shadow-sm);overflow:hidden;margin-bottom:18px;transition:box-shadow .25s}
.panel:hover{box-shadow:var(--shadow-md)}
.ph{padding:16px 20px 0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px}
.ph h6{margin:0;font-size:.88rem;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:8px}
.ph h6 i{color:var(--kkp-teal);font-size:1rem}
.pb{padding:12px 20px 20px}
.chart-c{display:flex;justify-content:center;align-items:center}

/* ═══ Grids ═══ */
.grid-4-8{display:grid;grid-template-columns:1fr 2fr;gap:18px;margin-bottom:18px}
.grid-8-4{display:grid;grid-template-columns:2fr 1fr;gap:18px;margin-bottom:18px}
.grid-3-3-6{display:grid;grid-template-columns:1fr 1fr 2fr;gap:18px;margin-bottom:18px}
.grid-full{margin-bottom:18px}
@media(max-width:1200px){.grid-3-3-6{grid-template-columns:1fr 1fr}.grid-3-3-6 > :last-child{grid-column:1 / -1}}
@media(max-width:992px){.grid-4-8,.grid-8-4,.grid-3-3-6{grid-template-columns:1fr}}

.leaflet-container{font-family:'Poppins',sans-serif}
.map-dot{width:14px;height:14px;border:2.5px solid #fff;border-radius:50%;cursor:pointer;transition:transform .25s ease}
[data-theme="dark"] .panel,[data-theme="dark"] .kc,[data-theme="dark"] .map-card{background:var(--bg-surface);border-color:var(--border-color)}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const isDark=document.documentElement.getAttribute('data-theme')==='dark';
    const gridC=isDark?'rgba(255,255,255,.07)':'rgba(0,0,0,.06)';
    const txtC=isDark?'#9CA3AF':'#6b7280';
    Chart.defaults.font.family="'Poppins',sans-serif";
    Chart.defaults.color=txtC;

    /* ═══ MAP ═══ */
    const map=L.map('mapEl',{zoomControl:false,scrollWheelZoom:true}).setView([-2.5,118],5);
    const lightTile='https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
    L.tileLayer(lightTile,{attribution:'© OpenStreetMap © CARTO',maxZoom:18}).addTo(map);
    L.control.zoom({position:'bottomright'}).addTo(map);

    const locs=@json($mapLocations);
    let mkrs=[];
    const fmt=v=>v?Number(v).toLocaleString('id-ID'):'0';
    const fmtRp=v=>v?'Rp '+Number(v).toLocaleString('id-ID'):'Rp 0';

    // Overlay elements
    const mapOverlay=document.getElementById('mapOverlay');
    const msContent=document.getElementById('msContent');
    const msNo=document.getElementById('msNo');
    const msName=document.getElementById('msName');
    const msLoc=document.getElementById('msLoc');
    const msStatus=document.getElementById('msStatus');
    const msKom=document.getElementById('msKom');
    const msVol=document.getElementById('msVol');
    const msNilai=document.getElementById('msNilai');
    const msBiaya=document.getElementById('msBiaya');
    const msSR=document.getElementById('msSR');
    const msKolam=document.getElementById('msKolam');
    const msKendalaSection=document.getElementById('msKendalaSection');
    const msKendala=document.getElementById('msKendala');

    let activeMarker=null;

    window.closeMapOverlay = function() {
        mapOverlay.classList.remove('show');
        if(activeMarker) {
            activeMarker.getElement().querySelector('.map-dot').style.transform='scale(1)';
            activeMarker=null;
        }
    };

    function showSidebar(l){
        msNo.textContent='KDMP #'+(l.no||l.id);
        msName.textContent=l.name;
        msLoc.textContent=(l.desa?l.desa+' · ':'')+l.kabupaten+', '+l.provinsi;
        msStatus.textContent=l.status;
        msStatus.style.background=l.color;
        msKom.textContent=l.komoditas||'-';
        msVol.textContent=fmt(l.produksi)+' kg';
        msNilai.textContent=fmtRp(l.nilai);
        msBiaya.textContent=fmtRp(l.biaya);
        msSR.textContent=l.sr?l.sr+'%':'-';
        msKolam.textContent=l.kolam_aktif!==null?l.kolam_aktif+' / '+l.kolam_total:'-';
        if(l.kendala){msKendalaSection.style.display='block';msKendala.textContent=l.kendala;}
        else{msKendalaSection.style.display='none';}
        mapOverlay.classList.add('show');
    }
    locs.forEach(l=>{
        if(!l.lat||!l.lng)return;
        const icon=L.divIcon({
            html:`<div class="map-dot" style="background:${l.color};box-shadow:0 0 0 3px ${l.color}30,0 2px 8px ${l.color}40"></div>`,
            className:'',iconSize:[14,14],iconAnchor:[7,7]
        });
        const m=L.marker([l.lat,l.lng],{icon}).addTo(map);
        m.on('click',()=>{
            showSidebar(l);
            if(activeMarker)activeMarker.getElement().querySelector('.map-dot').style.transform='scale(1)';
            const dot=m.getElement().querySelector('.map-dot');
            dot.style.transform='scale(1.6)';
            activeMarker=m;
            map.panTo([l.lat,l.lng],{animate:true});
        });
        mkrs.push(m);
    });
    if(mkrs.length) map.fitBounds(L.featureGroup(mkrs).getBounds().pad(.1));

    /* ═══ RADAR ═══ */
    new Chart(document.getElementById('cRadar'),{type:'radar',data:{labels:['Bangunan','Kolam','Listrik','Air','Aerasi'],datasets:[{data:[{{$fisikKomponen['Bangunan']}},{{$fisikKomponen['Kolam']}},{{$fisikKomponen['Listrik']}},{{$fisikKomponen['Air']}},{{$fisikKomponen['Aerasi']}}],backgroundColor:'rgba(8,145,178,.15)',borderColor:'#0891B2',pointBackgroundColor:'#0891B2',pointBorderColor:'#fff',borderWidth:2}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{r:{angleLines:{color:gridC},grid:{color:gridC},pointLabels:{font:{size:12,weight:'600'},color:txtC},ticks:{display:false},min:0,max:100}}}});

    /* ═══ BAR PROVINSI ═══ */
    const prov=@json($prodPerProvinsi);
    new Chart(document.getElementById('cBar'),{type:'bar',data:{labels:prov.map(d=>d.provinsi),datasets:[{data:prov.map(d=>d.total),backgroundColor:'rgba(8,145,178,.65)',borderRadius:4,maxBarThickness:36}]},options:{responsive:true,maintainAspectRatio:false,indexAxis:'y',plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>c.parsed.x.toLocaleString('id-ID')+' kg'}}},scales:{x:{beginAtZero:true,grid:{color:gridC},ticks:{callback:v=>v>=1e6?(v/1e6).toFixed(1)+'M':v>=1e3?(v/1e3).toFixed(0)+'k':v}},y:{grid:{display:false},ticks:{font:{size:11}}}}}});

    /* ═══ BAR TREN PRODUKSI ═══ */
    new Chart(document.getElementById('cTrend'),{
        type:'bar',
        data:{
            labels:['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'],
            datasets:[
                {
                    label:'Nilai Produksi (Rp)',
                    data:@json($nilaiBulanan),
                    backgroundColor:'rgba(16,185,129,.85)',
                    borderRadius:4,
                    yAxisID:'y1'
                },
                {
                    label:'Volume Panen (kg)',
                    data:@json($prodBulanan),
                    backgroundColor:'rgba(59,130,246,.85)',
                    borderRadius:4,
                    yAxisID:'y'
                }
            ]
        },
        options:{
            responsive:true,
            maintainAspectRatio:false,
            interaction:{mode:'index',intersect:false},
            plugins:{
                legend:{
                    position:'top',
                    labels:{usePointStyle:true,padding:16,font:{size:12}}
                }
            },
            scales:{
                y1:{
                    position:'left',
                    beginAtZero:true,
                    grid:{color:gridC},
                    title:{display:true,text:'Nilai Produksi (Rp)',font:{size:11}},
                    ticks:{callback:v=>v>=1e6?'Rp '+(v/1e6).toFixed(0)+'M':v}
                },
                y:{
                    position:'right',
                    beginAtZero:true,
                    grid:{display:false},
                    title:{display:true,text:'Volume Panen (kg)',font:{size:11}},
                    ticks:{callback:v=>v>=1e3?(v/1e3)+'k':v}
                },
                x:{grid:{display:false}}
            }
        }
    });

    /* ═══ KOMODITAS DOUGHNUT ═══ */
    const kd=@json($sebaranKomoditas);
    if(kd.length){
        const cols=['#3B82F6','#10B981','#F59E0B','#8B5CF6','#EC4899','#06B6D4','#F43F5E','#84CC16'];
        new Chart(document.getElementById('cKom'),{type:'doughnut',data:{labels:kd.map(d=>d.komoditas),datasets:[{data:kd.map(d=>d.total),backgroundColor:cols.slice(0,kd.length),borderWidth:0,hoverOffset:6}]},options:{responsive:true,maintainAspectRatio:false,cutout:'65%',plugins:{legend:{position:'bottom',labels:{padding:14,usePointStyle:true,pointStyle:'circle',font:{size:11}}}}}});
    }
});
</script>
@endpush
