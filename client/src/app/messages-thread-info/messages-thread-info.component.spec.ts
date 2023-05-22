import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MessagesThreadInfoComponent } from './messages-thread-info.component';

describe('MessagesThreadInfoComponent', () => {
  let component: MessagesThreadInfoComponent;
  let fixture: ComponentFixture<MessagesThreadInfoComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MessagesThreadInfoComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(MessagesThreadInfoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
