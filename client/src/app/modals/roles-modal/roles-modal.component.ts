import { AnimateTimings } from '@angular/animations';
import { Component, Input, OnInit } from '@angular/core';
import { BsModalRef } from 'ngx-bootstrap/modal';

@Component({
  selector: 'app-roles-modal',
  templateUrl: './roles-modal.component.html',
  styleUrls: ['./roles-modal.component.scss']
})
export class RolesModalComponent  implements OnInit{
  username = '';
  availableRoles: any[] = [];
  selectedRole: string='user';

  constructor(public bsModalRef: BsModalRef){ }

  ngOnInit(): void {
    //this.selectedRole='user';
  }


  update(event: Event) {
    const value = (event.target as HTMLSelectElement).value;
    this.selectedRole=value;
    console.log(this.selectedRole);
  }
}
