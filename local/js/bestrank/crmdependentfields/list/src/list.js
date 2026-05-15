import {EventEmitter} from 'main.core.events';

export class List {
	constructor(dependentFields) {
		this.stageId = null;
		this.dependentFields = dependentFields;

		this.replaceCrmProgressControlOnSaveRequestSuccess(dependentFields);

		EventEmitter.subscribe('CrmProgressControlBeforeSave', this.crmProgressControlBeforeSaveHandler.bind(this));
	}

	crmProgressControlBeforeSaveHandler(event: BaseEvent) {
		const [, params] = event.getData();
		this.stageId = params['VALUE'];
	}

	replaceCrmProgressControlOnSaveRequestSuccess(dependentFields) {
		let _this = this;

		(function (_onSaveRequestSuccess) {
			BX.CrmProgressControl.prototype._onSaveRequestSuccess = function (data) {
				const [entityType, itemId, required] = _this.getRequiredParamsForRequest(data.ID, _this.stageId);

				if (required) {
					BX.ajax.runAction('bestrank:crmdependentfields.Kanban.GetRequiredEmptyFields', {
						mode: 'class',
						data: {entityType, itemId, codeParam: _this.dependentFields.CODE_PARAM, stageId: _this.stageId}
					}).then((response) => {
						if (response.status == "success") {
							if (response.data) {
								if (data['CHECK_ERRORS']) {
									data['CHECK_ERRORS'] = {...data['CHECK_ERRORS'], ...response.data};
								} else {
									data['CHECK_ERRORS'] = response.data;
								}
							}
						}
						return _onSaveRequestSuccess.apply(this, arguments);
					}, (reason) => {
						console.log('reason', reason);
						return _onSaveRequestSuccess.apply(this, arguments);
					});
				} else {
					return _onSaveRequestSuccess.apply(this, arguments);
				}
			};
		})(BX.CrmProgressControl.prototype._onSaveRequestSuccess);
	}

	getRequiredParamsForRequest(itemId, stageId) {
		let fieldId = 'STAGE_ID';
		if (!this.dependentFields?.FIELDS[fieldId]) {
			fieldId = 'STATUS_ID';
		}

		if (!this.dependentFields?.FIELDS[fieldId]) {
			return [];
		}

		let stageFieldParams = this.dependentFields.FIELDS[fieldId];

		if (
			stageFieldParams.target != "fields" ||
			stageFieldParams.type != "bind" ||
			!stageFieldParams?.fields?.required
		) {
			return [];
		}

		const entityType = this.dependentFields?.ENTITY_TYPE,
			required = stageFieldParams.fields.required[stageId];

		return [entityType, itemId, required];
	}
}