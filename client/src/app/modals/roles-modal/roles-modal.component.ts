import { AnimateTimings } from '@angular/animations';
import { Component } from '@angular/core';
import { BsModalRef } from 'ngx-bootstrap/modal';

@Component({
  selector: 'app-roles-modal',
  templateUrl: './roles-modal.component.html',
  styleUrls: ['./roles-modal.component.scss']
})
export class RolesModalComponent {
  username = '';
  availableRoles: any[] = [];
  selectedRole: string='';

  constructor(public bsModalRef: BsModalRef){ }

  update(event: Event) {
    const value = (event.target as HTMLSelectElement).value;
    this.selectedRole=value;
    console.log(this.selectedRole);
  }
}
