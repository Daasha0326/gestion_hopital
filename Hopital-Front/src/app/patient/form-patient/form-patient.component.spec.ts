import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FormPatientComponent } from './form-patient.component';

describe('FormPatientComponent', () => {
  let component: FormPatientComponent;
  let fixture: ComponentFixture<FormPatientComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [FormPatientComponent]
    });
    fixture = TestBed.createComponent(FormPatientComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
