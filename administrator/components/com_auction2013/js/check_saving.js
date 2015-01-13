/**
 * Функция переназначается на событие click для кнопки отправки данных формы
 * @returns false
 */
function checkOnClick(action){
    //savingState.setState(true);
    console.log('%ccheckOnSubmit','font-weight:bold;color:orange');
    checkFormData(/*false,*/action); // apply|save
    return false;
}
/**
 * Проверить форму перед сохранением данных предмета
 * @param auctionNumberInput - поле с номером аукциона
 * @returns boolean
 */
function checkFormData(/*auctionNumberInput,*/action) {
    //console.log('auctionNumberInput:');
    //console.dir(auctionNumberInput);
    var $ = jQuery;
    //var messageBlock = $('#checking_result');
    // получить Id id категорий для онлайн-торгов
    var fulltimeIds = getFullTimeIds();
    var cat_number, category_id, fulltime = false;
    // список предметов с категориями
    var cats_options = $('.inputbox.chzn-done[name="categories[]"] option');
    // выбранные категории
    var shoosenCats = $('ul.chzn-choices li.search-choice');
    $(shoosenCats).each(function (index, element) {
        cat_number = element.id.substr(element.id.lastIndexOf('_') + 1);
        category_id = parseInt($(cats_options).eq(cat_number).val());
        // есть выбранные предметы
        if (fulltimeIds.indexOf(category_id) != -1) {
            fulltime = true; // значит - очные торги
            //console.log('in fulltime!');
            return true; // прервать цикл
        }
    });
    // получить поле с № аукциона, если ещё не получено
    //if(!auctionNumberInput) auctionNumberInput = $('input[name="auction_number"]');

    if(action){ // клацали по кнопке
        var errMess=[];
        //
        /*if (fulltime && !$(auctionNumberInput).val())
            errMess.push('Не указан номер аукциона');*/
        //
        if($(shoosenCats).size() === 0)
            errMess.push('Не выбрана категория предмета');
        //
        if(!$('#product_name').val())
            errMess.push('Не указано название предмета');
        // обнаружены ошибки
        if(errMess.length){
            errMess=errMess.join('\n');
            alert(errMess);
            return false;
        }
        console.log("%cПроверка пройдена, идём дальше!",'font-weight: bold; color:green;');
    }

    var removeInfoBlock = function(){
        var blockId = 'checking_result';
        $('#'+blockId).remove(); // удалить сообщение, если уже есть
        return blockId;
    };
    // если очные торги, будем проверять дату аукциона:
    if (fulltime) {
        // создать/разместить/вернуть для alert'а (в случае сохранения) сообщение об ошибке
        var setInfoBlock = function (infoClass) { // ok/taken/empty
            var blockId = removeInfoBlock(); // удалить сообщение, если уже есть
            //console.dir($('#checking_result'));
            var title = 'Номер аукциона ';
            switch (infoClass) {
                case 'ok':
                    title += 'свободен';
                    break;
                case 'taken':
                    title += 'занят';
                    break;
                default:
                    title += 'не указан';
            }
            var info = $('<div/>', {
                id: blockId,
                class: infoClass,
                title: title
            });
            //$(auctionNumberInput).after(info);
            return title;
        };
        //--------------------------------------------
        // нет номера аукциона (при потере фокуса поля), - всё отменить
        /*if (!$(auctionNumberInput).val()) {
            // если НЕ клацали по кнопке сохранения данных
            if (!action)
                setInfoBlock('empty'); // также удаляет сообщение, если оно уже есть
            console.log('129: empty');
            return false;
        } else { // номер аукциона есть
            // есть не тот, что был - проверить доступность
            var gotoUrl = getUrlToGo($(auctionNumberInput).val());
            console.log('Проверить № аукциона: '+gotoUrl);
            $.get(gotoUrl)
                .success(
                function (data) {
                    // показать и вернуть результат проверки
                    var message = setInfoBlock(data);
                    //console.log('data: ' + data);
                    if (data == 'taken') { // если неудачно
                        // если клацали по кнопке сохранения данных
                        if (action) {
                            alert(message);
                        }  // к этому моменту выводит сообщение (см. выше, var message)
                        return false;
                    } else{
                        // если клацали по кнопке
                        if (action) {
                            Joomla.submitbutton(action); // отправить данные формы
                            //alert('[156]Отправить данные');
                            //console.log('%cОтправить данные', 'color:blue');
                        }  // к этому моменту выводит сообщение (см. выше, var message)
                        return true;
                    }
                })
                .error(
                    function () {
                        alert('ОШИБКА: не удалось проверить номер аукциона.');
                        return false;
                });
            //}
        }*/
    } /*else if (action) {
        removeInfoBlock();
        // Не очные торги и клацали по кнопке
        Joomla.submitbutton(action); // отправить данные формы
        //alert('[156]Отправить данные');
        //console.log('%c[2]Отправить данные', 'color:violet');
    }*/
    Joomla.submitbutton(action);
}
