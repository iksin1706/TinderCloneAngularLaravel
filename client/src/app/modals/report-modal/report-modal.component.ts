import { Component } from '@angular/core';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap/modal';
import { ToastrService } from 'ngx-toastr';
import { AdminService } from 'src/app/_services/admin.service';
import { MembersService } from 'src/app/_services/members.service';

@Component({
  selector: 'app-report-modal',
  templateUrl: './report-modal.component.html',
  styleUrls: ['./report-modal.component.scss']
})
export class ReportModalComponent {
  username = '';
  availableRoles: any[] = [];
  reportForm: FormGroup = new FormGroup({});
  now = new Date();

  ngOnInit(): void {
    this.initializeForm();
  }

  initializeForm() {
    this.reportForm = this.fb.group({
      reason: ['', Validators.required],
    });
  }

  constructor(public bsModalRef: BsModalRef, private fb: FormBuilder,private membersService: MembersService,private toaster:ToastrService){;
   }

  update(event: Event) {
    const value = (event.target as HTMLSelectElement).value;

  }

  report(){
    let values = {...this.reportForm.value}
    this.membersService.reportUser(this.username,values).subscribe({
      next: response => this.toaster.success(response)
    })
  }
}
