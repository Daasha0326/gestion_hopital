import { Component, Input, OnInit } from '@angular/core';
import { Consultation } from 'src/app/interface/liste';
import { PatientService } from 'src/app/service/patient.service';

@Component({
  selector: 'app-consultation',
  templateUrl: './consultation.component.html',
  styleUrls: ['./consultation.component.css']
})
export class ConsultationComponent implements OnInit {

  @Input() consultations: Consultation[] = []

  tableSizes: number[] = []
  tableSize: number = 5;
  count: number = 0
  page: number = 1

  constructor(private listeService: PatientService,) {

  }
  ngOnInit() {}

  onTableChange(event: any) {
    this.tableSizes = event.target.value;
    this.page = 1;
  }


}
