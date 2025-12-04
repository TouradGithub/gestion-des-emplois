// Initialize Gantt chart
gantt.config.date_format = "%Y-%m-%d";
gantt.config.scale_unit = "month"; 
gantt.config.date_scale = "%F %Y"; 
gantt.config.subscales = [
    {unit: "week", step: 1, date: "%d %M"} 
];

// Configure columns 
// gantt.config.columns = [];
gantt.config.columns = [
    {name: "text", label: " Task Name", tree: true, width: 300},
];

// Initial data
var tasks = {
    data: [],
    links: [
        {id: 1, source: 2, target: 3, type: "0"},
        {id: 2, source: 3, target: 4, type: "0"}
    ]
};

// Initialize chart
gantt.init("gantt_here");
gantt.parse(tasks);

// Enable drag and drop
gantt.config.drag_resize = true;
gantt.config.drag_links = true;

// Update date range when date fields change
function updateDateRange() {
    const startDate = document.getElementById('date_deb_gantt').value;
    const endDate = document.getElementById('date_fin_gantt').value;
    
    if (startDate && endDate) {
        gantt.config.start_date = new Date(startDate);
        gantt.config.end_date = new Date(endDate);
        gantt.render();
    }
}

// Update scale unit (daily/weekly)
function updateScaleUnit() {
    const scaleUnit = document.getElementById('scale_unit').value;
    
    gantt.config.scale_unit = "month";
    gantt.config.date_scale = "%F %Y";
    
    if (scaleUnit === 'day') {
        gantt.config.subscales = [
            {unit: "day", step: 1, date: "%d"},
        ];
    } else {
        gantt.config.subscales = [
            {unit: "week", step: 1, date: "Week %W"},
        ];
    }
    
    gantt.render();
}

// Set default dates
window.addEventListener('load', () => {
    const defaultStart = new Date('2025-01-01');
    const defaultEnd = new Date('2025-02-28');
    
    document.getElementById('date_deb_gantt').value = defaultStart.toISOString().split('T')[0];
    document.getElementById('date_fin_gantt').value = defaultEnd.toISOString().split('T')[0];

    getChartDiagram();
    
    updateDateRange();
});

// Add event listeners

document.getElementById('date_deb_gantt').addEventListener('change', getChartDiagram);
document.getElementById('date_fin_gantt').addEventListener('change', getChartDiagram);
document.getElementById('scale_unit').addEventListener('change', updateScaleUnit);

// Update event listener
gantt.attachEvent("onAfterTaskUpdate", function(id, item) {
    console.log("Task updated:", item);
});


function getChartDiagram(){
  
    
    const projectSelect = document.getElementById("project-select");
    const dateDeb = document.getElementById("date_deb_gantt");
    const dateFin = document.getElementById("date_fin_gantt");
    const projectId =  projectSelect.value;
    const dateDebVal =  dateDeb.value;
    const dateFinVal =  dateFin.value;
    console.log(dateDebVal);
    console.log("F:"+dateFinVal);
    console.log(projectId);
    if( projectId && dateDebVal &&  dateFinVal){
        
        fetch(`getTasks/?project_id=${projectId}&&dete_deb=${dateDebVal}&&date_fin=${dateFinVal}`)
        .then(response => response.json())
        .then(data => {
            if (data.status == 'success') {
                gantt.clearAll();
                
                tasks.data = [];
                tasks.data = data.tasks;

                gantt.init("gantt_here");
                gantt.parse(tasks);
                updateDateRange();
                console.log( tasks.data);

            }
        })
        .catch(error => {
            console.error("Error fetching lists:", error);

        });
    }


}
