import { DatePipe } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap/modal';
import { ToastrService } from 'ngx-toastr';
import { AdminService } from 'src/app/_services/admin.service';

@Component({
  selector: 'app-bans-modal',
  templateUrl: './bans-modal.component.html',
  styleUrls: ['./bans-modal.component.scss']
})
export class BansModalComponent implements OnInit{
  username = '';
  availableRoles: any[] = [];
  banForm: FormGroup = new FormGroup({});
  now = new Date();

  ngOnInit(): void {
    this.initializeForm();
  }

  initializeForm() {
    this.banForm = this.fb.group({
      reason: ['', Validators.required],
      until: ['', Validators.required],
    });
  }

  constructor(public bsModalRef: BsModalRef, private fb: FormBuilder,private adminService: AdminService,private toaster:ToastrService){;
   }

  update(event: Event) {
    const value = (event.target as HTMLSelectElement).value;

  }


  ban(){
    let values = {...this.banForm.value}
    this.adminService.banUser(this.username,values).subscribe({
      next: response => {
         this.toaster.success(response);
      }
    })
  }
}


