<template>

  <br /> <br /> <br /> <br />
  <ejs-schedule height='550px' width='100%' :selectedDate='selectedDate' :eventSettings='eventSettings'>
    <e-views>
      <e-view option='Day'></e-view>
      <e-view option='Week' startHour='07:00' endHour='15:00'></e-view>
      <e-view option='WorkWeek' startHour='10:00' endHour='18:00'></e-view>
      <e-view option='Month' showWeekend=false></e-view>
      <e-view option='Agenda'></e-view>
    </e-views>
    <e-resources>
      <e-resource field="OwnerId" title="Owner" name="Owners" :dataSource="ownerDataSource" textField="OwnerText"
        idField="Id" colorField="OwnerColor">
      </e-resource>
    </e-resources>
    <e-header-rows>
      <e-header-row option="Date"></e-header-row>
      <e-header-row option="Hour"></e-header-row>
    </e-header-rows>
  </ejs-schedule>

</template>

<script>
import { ScheduleComponent, Day, Week, WorkWeek, Month, Agenda, DragAndDrop, Resize, HeaderRowsDirective, HeaderRowDirective, ViewsDirective, ViewDirective, ResourcesDirective, ResourceDirective } from "@syncfusion/ej2-vue-schedule";
import axios from 'axios';
import '../../../../node_modules/@syncfusion/ej2-base/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-buttons/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-calendars/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-dropdowns/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-inputs/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-navigations/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-popups/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-vue-schedule/styles/material.css';

export default {
  name: "App",
  data() {
    return {
      selectedDate: new Date(),
      group: { resources: ['Owners'] },
      allowMultiple: true,
      ownerDataSource: [
        { OwnerText: 'Séance', Id: 1, OwnerColor: '#9dcd5a' },
        { OwnerText: 'Examen', Id: 2, OwnerColor: '#DF7588' },
        { OwnerText: 'Michael', Id: 3, OwnerColor: '#7499e1' },
      ],
      eventSettings: {
        dataSource: []
      }
    };
  },
  provide: {
    schedule: [Day, Week, WorkWeek, Month, Agenda, DragAndDrop, Resize]
  },
  methods: {
     createDateFromStrings(dateString, timeString) {
  // Split the date string into parts
  const [year, month, day] = dateString.split('-').map(Number);

  // Split the time string into parts
  const [hours, minutes, seconds] = timeString.split(':').map(Number);

  // Note: Month in JavaScript Date is 0-indexed (0 = January, 1 = February, etc.)
  return new Date(year, month - 1, day, hours, minutes, seconds);
},
    async fetchSeancesAndExamens() {
      try {
        const response = await axios.get('/seance/index');
        const { seances, examens } = response.data;
        const updatedExamens = examens.map(element => ({
          Id: element.id,
          Subject: element.type,
          EventType: element.status,
          StartTime: this.createDateFromStrings(element.dateE, element.heureD),
          EndTime: this.createDateFromStrings(element.dateE, element.heureF),
          OwnerId: 2
        }));
        const updatedSeance = seances.map(element => ({
          Id: element.id,
          Subject: element.type,
          EventType: element.status,
          StartTime: this.createDateFromStrings(element.dateS, element.heureD),
          EndTime: this.createDateFromStrings(element.dateS, element.heureF),
          OwnerId: 1
        }));
   // Combine both datasets
        const updatedEvents = [...updatedSeance, ...updatedExamens]; 
         // Update dataSource reactively
         this.eventSettings = {
          ...this.eventSettings,
          dataSource: updatedEvents
        };
      } catch (error) {
        console.error('Error fetching seances and examens:', error);
      }
    }
  },
  async mounted() {
    let isSuperAdmin = JSON.parse(localStorage.getItem('users'))[0].role === "superAdmin";
    if (isSuperAdmin) {
      window.location.href = '/dashbord_Super_Admin';
    } else {
      await this.fetchSeancesAndExamens();
    }

  },
  components: {
    'ejs-schedule': ScheduleComponent,
    'e-views': ViewsDirective,
    'e-view': ViewDirective,
    'e-resources': ResourcesDirective,
    'e-resource': ResourceDirective,
    'e-header-rows': HeaderRowsDirective,
    'e-header-row': HeaderRowDirective
  }
};

</script>
